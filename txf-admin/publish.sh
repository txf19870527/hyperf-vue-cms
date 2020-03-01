#!/bin/bash
cp -r ./app/Service/Interfaces/ ../txf-server/app/Service/Interfaces/
cp -r ./app/Com/ResponseCode.php ../txf-server/app/Com/ResponseCode.php
cp -r ./app/Com/RedisKeyMap.php ../txf-server/app/Com/RedisKeyMap.php
cp -r ./app/Com/Log.php ../txf-server/app/Com/Log.php
rm -rf ./runtime/container/proxy/
rm -rf ../txf-server/runtime/container/proxy/

echo "publish success"