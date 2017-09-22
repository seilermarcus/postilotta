#!/bin/bash

##################################################
#
# Build pta instance via jenkins job
#
# @param $1: system abbr. to build
# @param $2: Reuse of certificate (TRUE or FALSE)
# @param [$3]: db password, default pulled from 700 files
#
##################################################

# check sys argument
if [ -z "$3" ]
  then
    dbpas=$(cat /etc/pta/$syst-sql)
  else
    dbpas=$3
fi

# Set variables
syst=$1
reuse=$2
db="pta_$syst-sql"
php="pta_$syst-php"

# Start script with standard message output
start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")
echo "START build of $syst at $now"

# Go up to project roo dir
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

# write sql password to .inc file
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
    -e "VIRTUAL_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=$reuse" \
    --name $php \
    --link $db:mysql \
   $php
#    -e "LETSENCRYPT_TEST=true" \
#    -e "DEBUG=true" \

# wait for mysql server to come up
echo 'waiting for mysql server (50 sec)'
sleep 50

# initialise DB scheme
docker exec $db bash -c "mysql -u root -p$dbpas < /tmp/init.sql"

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END build of $syst after $runtime sec"
