#!/bin/bash

read -p "Let's Encrypt certificate REUSE_KEY (TRUE / FALSE): " reuse

# setup jenkins container
docker run \
    --name "pta_jenkins" \
    --expose=80 \
    --expose=50000 \
    -v jenkins_home:/var/jenkins_home \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -v $(which docker):/usr/bin/docker \
    -v /var/postilotta/prototype:/var/postilotta/prototype \
    -v /srv:/srv \
    -v /usr/lib/x86_64-linux-gnu/libltdl.so.7:/usr/lib/x86_64-linux-gnu/libltdl.so.7 \
    --env JENKINS_OPTS="--httpPort=80" \
    -e "VIRTUAL_HOST=jenkins.postilotta.com" \
    -e "LETSENCRYPT_HOST=jenkins.postilotta.com" \
    -e "LETSENCRYPT_EMAIL=marcus.seiler@posteo.de" \
    -e "REUSE_KEY=$reuse" \
    -d jenkins/jenkins:lts

#    -v $(which docker):/usr/bin/docker \
#    --expose 8080
#    --env JENKINS_OPTS="--prefix=/jenkins" \

echo 'pta: done'
