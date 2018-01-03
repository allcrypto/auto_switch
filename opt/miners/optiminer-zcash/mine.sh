#!/bin/bash

# General options

# The following line did not have any effect on my system but might reduce CPU usage on some.
# export GPU_SYNC_OBJECTS=1
export GPU_FORCE_64BIT_PTR=1

# Example pools
echo "Adjust the pool data below and remove this line!"

# Flypool (encrypted connection):
# uses cash address as user
POOL=zstratum+tls://eu1-zcash.flypool.org:3443
USER=t1Yszagk1jBjdyPfs2GxXx1GWcfn6fdTuFJ.worker
PASSWORD=x

# Nicehash:
# uses bitcoin address as user
#POOL=equihash.usa.nicehash.com:3357
#USER=19STEagfLfbb1XdTF9NCf5kmxZHGchSiZj
#PASSWORD=x

# suprnova.cc
#POOL=zec.suprnova.cc:2142
#USER=Weblogin.Worker
#PASSWORD=WorkerPassword

cd "$(dirname "$0")"
while true
do
  ./optiminer-zcash -s $POOL -u $USER -p $PASSWORD --watchdog-timeout 30 --watchdog-cmd "./watchdog-cmd.sh"
  if [ $? -eq 134 ]
  then
    break
  fi
done

