#!/bin/bash

tpwd=$PWD
dir=${PWD##*/}
db="$dir-sql"
php="$dir-php"

read -p "System (dev, test,...): " syst

read -p "Let's Encrypt certificate REUSE_KEY (TRUE / FALSE): " reuse

# setup mysql container
cd db/
docker build -t $db . \
&& docker run \
    --name $db \
    -v /srv/$db/:/var/lib/mysql/ \
    -e MYSQL_ROOT_PASSWORD=123456 \
    -d mysql \
    --max-allowed-packet=67108864 \
&& docker cp init.sql $db:/tmp/init.sql

# setup php container
cd ../php/
docker build -t $php . \
&& docker run \
    -d \
    -v $tpwd/www:/var/www/html/ \
    --expose=80 \
    -e "VIRTUAL_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_HOST=$syst.postilotta.com" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=$reuse" \
    --name $php \
    --link $db:mysql \
   $php
#    -e "LETSENCRYPT_TEST=true" \
#    -e "LETSENCRYPT_HOST=postilotta.com, $syst.postilotta.com" \
#    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \

#    -e "LETSENCRYPT_TEST=true" \
#    -e "LETSENCRYPT_HOST=postilotta.com, $syst.postilotta.com" \
#    -p $port:80 \
#    -e "LETSENCRYPT_TEST=true" \
#    -e "DEBUG=true" \

# write sql container-id to .inc file
sqlip=$(docker ps --filter "name=$db" --format "{{.ID}}")
out="<?php \$servername = \"$sqlip\"; ?>"
echo $out > "../www/inc/sql-ip.inc"
