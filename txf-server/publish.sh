#!/bin/bash

# 同步到admin
cp -r ./app/Service/Interfaces/ ../txf-admin/app/Service/Interfaces/
cp -r ./app/Com/ResponseCode.php ../txf-admin/app/Com/ResponseCode.php
cp -r ./app/Com/RedisKeyMap.php ../txf-admin/app/Com/RedisKeyMap.php

# 同步到api
cp -r ./app/Service/Interfaces/ ../txf-api/app/Service/Interfaces/
cp -r ./app/Com/ResponseCode.php ../txf-api/app/Com/ResponseCode.php
cp -r ./app/Com/RedisKeyMap.php ../txf-api/app/Com/RedisKeyMap.php


rm -rf ./runtime/container/proxy/
rm -rf ../txf-admin/runtime/container/proxy/
rm -rf ../txf-api/runtime/container/proxy/

echo "publish success"