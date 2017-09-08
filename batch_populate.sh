#!/bin/bash

db="pta_$1-sql"
dbpas=$2
docker cp db/init-data.sql $db:/tmp/init-data.sql
docker exec $db bash -c "mysql -uroot -p$dbpas postilotta_msgng < /tmp/init-data.sql"
