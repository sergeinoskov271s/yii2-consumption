#!/usr/local/bin/bash
# intersearch 

curl -X POST -H "Content-Type: application/json" \
--data '{"enterprise":{ "title":"Enterprise1","id":"4"},"department":{"title":"Department1","id":"5"}}' \
 http://192.168.1.1/personal/ajax/intersearch/getdepartment


exit 0  


































