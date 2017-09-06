#!/bin/bash

db="pta_$1-sql"
docker exec $db bash -c "mysql -uroot -p123456 postilotta_msgng < /tmp/init_data.sql"
