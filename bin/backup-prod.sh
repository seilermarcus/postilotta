#!/bin/bash

####################################
#
# Backup pta sql via dump and ftp
# (run with sudo)
#
# @param $1: system abbr. to backup, "prod" as default
# @param $2: ftp user name, prod system as default
#
####################################

start=`date +%s`

# check sys argument
if [ -z "$1" ]
  then
    sys="prod"
  else
    sys=$1
fi
if [ -z "$2" ]
  then
    ftpusr="d27590b"
  else
    ftpusr=$2
fi

# Prepare Variables
db="pta-sql"
today=$(date +"%Y-%m-%d_%H-%M")
archive_file="$db-$today.tgz"
backup_file="$db-$today.sql"

# read passwords from 700 files
ftppass=$(cat /etc/pta/ftpbackup)
sqlpass=$(cat /etc/pta/sql)

# Print start status message
echo "START backup $db-$today"

# Generate db dump, cp to ftp, clean up
docker exec $db bash -c "mysqldump --verbose --single-transaction postilotta_msgng -uroot -p$sqlpass > /tmp/$backup_file" \
&& docker cp $db:/tmp/$backup_file /srv/backup/pta_$sys/$backup_file \
&& cd /srv/backup/pta_$sys \
&& tar czf ./$archive_file $backup_file \
&& lftp -c "open -u $ftpusr,$ftppass backup.contabo.net; cd pta_$sys; put -E ./$archive_file;" \
&& docker exec $db bash -c "rm /tmp/$backup_file"\
&& rm $backup_file

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END backup after $runtime sec"

# mysqldump OPTION --no-create-info
