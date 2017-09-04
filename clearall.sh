#!/bin/bash
dir=${PWD##*/}
db="$dir-sql"
php="$dir-php"

docker stop $db && \
docker rm $db && \
docker rmi $db && \
sudo rm -rf /srv/$db

docker stop $php && \
docker rm $php && \
docker rmi $php && \
sudo rm -rf /srv/$php
rm ./www/inc/sql-ip.inc
