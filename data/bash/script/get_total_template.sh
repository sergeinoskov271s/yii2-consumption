#!/usr/local/bin/bash


curl -i \
-H "Accept: application/json" \
-X POST http://192.168.1.1/yii2-consumption/json/template/gettotal \
-H 'Content-Type: application/json'

