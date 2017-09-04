postilotta
==========

Under construction.

See prototype https://v03.postilotta.com

Prepare Environment
-------------------
0. docker
1. nginx-proxy
2. Let's Encrypt Companion
3. Jenkins

BUILD (without jenkins)
-----------------------
1. Copy files incl. folder structure to new folder, the folder name will be used as instance prefix in docker
2. run ./build1.sh to set up docker containers
  2.1 Input prompt asks for "System" to be used as <system>.postilotta.com
  2.2 Input pormpt asks for "REUSE_KEY": TRUE for rebuild existing instanze, FALSE for new.
  2.3 wait like 30 sec for mysql server to come up
3. run ./build2.sh to implement db schema
4. use importDB.sh to load initial data into the DB (if you so wish...)

Populate
--------
Use transport.sh to push whole project folder, except the instance-specific sql-ip file,
into another (running) instance folder.
