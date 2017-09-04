#!/bin/bash

dir=${PWD##*/}
db="$dir-sql"

read -p "File: " fl
docker cp $fl $db:/tmp/$fl
docker exec $db bash -c "mysql -uroot -p123456 postilotta_msgng < /tmp/$fl"
