#!/usr/local/bin/bash


curl -i \
-X POST -H 'Content-Type: application/json' -d '{"cat":"пит","param2":"2025-01-01"}' \
 http://192.168.1.1/yii2-consumption/json/template/gettotalcat \


