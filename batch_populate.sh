#!/bin/bash

db="pta_$1-sql"
docker cp db/init-data.sql $db:/tmp/init-data.sql
docker exec $db bash -c "mysql -uroot -p123456 postilotta_msgng < /tmp/init-data.sql"
