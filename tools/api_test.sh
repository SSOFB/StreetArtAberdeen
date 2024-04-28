#!/bin/bash  

source secret.sh

#curl -X GET https://streetartaberdeen.com/api/index.php/v1/content/articles

curl -X GET "https://streetartaberdeen.com/api/index.php/v1/content/articles" \
     -H "Content-Type: application/json" \
     -H "Accept: application/vnd.api+json" \
     -H "Authorization: Bearer $TOKEN"  