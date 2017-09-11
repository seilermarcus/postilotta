#!/bin/bash

syst=$1
reuse=$2
#dbpas=123456
dbpas=$3
tpwd=$PWD
#dir=${PWD##*/}
db="pta_$syst-sql"
php="pta_$syst-php"

# write sql password to .inc file
out="<?php \$password = \"$dbpas\"; ?>"
echo $out > "./php-jks/www/inc/sql-pwd.inc"

# setup mysql container
cd db/
docker build -t $db . \
&& docker run \
    --name $db \
    -v /srv/$db/:/var/lib/mysql/ \
    -e MYSQL_ROOT_PASSWORD=$dbpas \
    -d mysql \
    --max-allowed-packet=67108864 \
&& docker cp init2.sql $db:/tmp/init2.sql

# write sql container-id to .inc file
sqlip=$(docker ps --filter "name=$db" --format "{{.ID}}")
out="<?php \$servername = \"$sqlip\"; ?>"
echo $out > "../php-jks/www/inc/sql-ip.inc"

# setup php container without src-path mnt
cd ../php-jks/
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
docker exec $db bash -c "mysql -u root -p$dbpas < /tmp/init2.sql"
