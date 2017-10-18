#!/bin/bash

####################################
#
# Build poste.io mailserver for pta
# (run with sudo)
#
####################################

start=`date +%s`
now=$(date +"%Y-%m-%d_%H-%M")
mail="pta_mail"

# use argument or default
if [ -z "$1" ]
  then
    reuse="TRUE"
  else
    reuse=$1
fi




# Start script with standard message output
echo "START build of pta_mail at $now"

# clean up old container
docker stop $mail && \
docker rm $mail && \
docker rmi $mail && \
rm -rf /srv/$mail

# Create poste.io container
docker run \
    --expose=25 \
    --expose=80 \
    --expose=110 \
    --expose=143 \
    --expose=443 \
    --expose=465 \
    --expose=587 \
    --expose=993 \
    --expose=995 \
    -v /etc/localtime:/etc/localtime:ro \
    -v /srv/pta_mail/data:/data \
    -e "VIRTUAL_HOST=mail.postilotta.com" \
    -e "LETSENCRYPT_HOST=mail.postilotta.com" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=$reuse" \
    -e "HTTPS=OFF" \
    --name $mail \
    -t analogic/poste.io

#     -d analogic/poste.io    

# Finish script with standard output
end=`date +%s`
runtime=$((end-start))
echo "END build of $sys after $runtime sec"
