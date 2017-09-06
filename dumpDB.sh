#!/bin/bash
dir=${PWD##*/}
db="pta_$dir-sql"
today=`date +%Y-%m-%d`

docker exec $db bash -c "mysqldump postilotta_msgng -uroot -p123456 > /tmp/db_export_$today.sql"
docker cp $db:/tmp/db_export_$today.sql db_export_$today.sql
