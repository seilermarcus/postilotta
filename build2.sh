#!/bin/bash
dir=${PWD##*/}
db="$dir-sql"

docker exec $db bash -c "mysql -u root -p123456 < /tmp/init.sql"
