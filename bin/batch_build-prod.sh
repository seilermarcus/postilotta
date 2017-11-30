#!/bin/bash

##################################################
#
# Build pta productive system
#
##################################################


# Set variables
db="pta-sql"
php="pta-php"

# Start script with standard message output
start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")
echo "START build at $now"

# Go up to project root dir
cd ..
tpwd=$PWD

# clean up sql container
docker stop $db && \
docker rm $db && \
docker rmi $db && \
rm -rf /srv/$db

# clean up php container
docker stop $php && \
docker rm $php && \
docker rmi $php && \
rm php/www/inc/sql-ip.inc

# write SMTP password to .inc file
smtppas=$(cat /etc/pta/smtp)
pasfile="<?php \$smtp_password = \"$smtppas\"; ?>"
echo $pasfile > "./php/www/inc/smtp-pwd.inc"

# write sql password to .inc file
dbpas=$(cat /etc/pta/sql)
out="<?php \$password = \"$dbpas\"; ?>"
echo $out > "./php/www/inc/sql-pwd.inc"

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

# setup php container without src-path mnt
cd ../php/
docker build -t $php . \
&& docker run \
    -d \
    --expose=80 \
    -e "VIRTUAL_HOST=postilotta.org" \
    -e "LETSENCRYPT_HOST=postilotta.org" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=FALSE" \
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
echo "END build after $runtime sec"
