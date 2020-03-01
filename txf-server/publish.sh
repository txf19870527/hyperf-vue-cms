#!/bin/bash

cp -r ./app/Service/Interfaces/ ../txf-admin/app/Service/Interfaces/
cp -r ./app/Com/ResponseCode.php ../txf-admin/app/Com/ResponseCode.php
cp -r ./app/Com/RedisKeyMap.php ../txf-admin/app/Com/RedisKeyMap.php
cp -r ./app/Com/Log.php ../txf-admin/app/Com/Log.php
rm -rf ./runtime/container/proxy/
rm -rf ../txf-admin/runtime/container/proxy/


echo "publish success"