#!/bin/bash

####################################
#
# Build pta instance
# (run with sudo)
#
# @param $1: system abbr. to build
# @param $2: Reuse of certificate (TRUE or FALSE)
#
####################################

# Set variables
syst=$1
reuse=$2
db="pta_$syst-sql"
php="pta_$syst-php"

# Start script with standard message output
start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")
echo "START build of $syst at $now"

# Go up to project root dir
cd ..
tpwd=$PWD

# write SMTP password to .inc file
smtppas=$(cat /etc/pta/$syst-smtp)
pasfile="<?php \$smtp_password = \"$smtppas\"; ?>"
echo $pasfile > "./php/www/inc/smtp-pwd.inc"

# write sql password to .inc file
dbpas=$(cat /etc/pta/$syst-sql)
pasfile="<?php \$password = \"$dbpas\"; ?>"
echo $pasfile > "./php/www/inc/sql-pwd.inc"

# setup mysql container
cd db/
docker build -t $db . \
&& docker run \
    --name $db \
    -v /srv/$db/:/var/lib/mysql/ \
    -e MYSQL_ROOT_PASSWORD=$dbpas \
    -d mysql \
    --max-allowed-packet=67108864 \
&& docker cp init.sql $db:/tmp/init.sql

# write sql container-id to .inc file
sqlip=$(docker ps --filter "name=$db" --format "{{.ID}}")
out="<?php \$servername = \"$sqlip\"; ?>"
echo $out > "../php/www/inc/sql-ip.inc"

# setup php container
cd ../php-dev/
docker build -t $php . \
&& docker run \
    -d \
    -v $tpwd/php/www:/var/www/html/ \
    --expose=80 \
    -e "VIRTUAL_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=$reuse" \
    --name $php \
    --link $db:mysql \
   $php
#    -e "LETSENCRYPT_TEST=true" \
#    -e "DEBUG=true" \

# install PEAR Mail package
docker exec $php bash -c "pear install Mail"
docker exec $php bash -c "pear install Net_SMTP"

# wait for mysql server to come up
echo 'waiting for mysql server (50 sec)'
sleep 50

# initialise DB scheme
docker exec $db bash -c "mysql -u root -p$dbpas < /tmp/init.sql"

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END build of $syst after $runtime sec"
