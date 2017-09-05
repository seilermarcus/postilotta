#!/bin/bash

db="pta_$1-sql"
php="pta_$1-php"

docker stop $db && \
docker rm $db && \
docker rmi $db && \
sudo rm -rf /srv/$db

docker stop $php && \
docker rm $php && \
docker rmi $php && \
sudo rm -rf /srv/$php
rm php-jks/www/inc/sql-ip.inc
