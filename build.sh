#!/bin/bash

#read -p "System (dev, test,...): " syst
#read -p "Let's Encrypt certificate REUSE_KEY (TRUE / FALSE): " reuse

# Clear up before build
dir=${PWD##*/}
./batch_clearall.sh $dir

# Build dev environment
./batch_build.sh ${PWD##*/} 'TRUE' '123456'
