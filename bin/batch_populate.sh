#!/bin/bash

############################################################
#
# Fills db of pta <sys> with data out of init-data.sql file
#
#############################################################

db="pta_$1-sql"
dbpas=$(cat /etc/pta/$syst-sql)

# Go to project root dir
cd ..

docker cp db/init-data.sql $db:/tmp/init-data.sql
docker exec $db bash -c "mysql -uroot -p$dbpas postilotta_msgng < /tmp/init-data.sql"
