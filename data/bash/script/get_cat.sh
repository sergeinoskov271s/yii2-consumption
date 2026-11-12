#!/usr/local/bin/bash


curl -i \
-H "Accept: application/json" \
-X POST http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler \
-H 'Content-Type: application/json' -d '{"order":"cat","param2":"2025-01-01"}'

