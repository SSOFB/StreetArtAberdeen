#!/bin/bash  

source secret.sh

#curl -X GET https://streetartaberdeen.com/api/index.php/v1/content/articles

curl -X GET "https://streetartaberdeen.com/api/index.php/v1/content/articles" \
     -H "Content-Type: application/json" \
     -H "Accept: application/vnd.api+json" \
     -H "Authorization: Bearer $TOKEN"  

curl -X GET "https://streetartaberdeen.com/api/index.php/v1/content/articles" -H "Authorization: Bearer $TOKEN"  


#curl --location --request GET "http://127.0.0.1:8000/api/index.php/v1/users" --header "X-Joomla-Token: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"


curl --location --request GET "https://streetartaberdeen.com/api/index.php/v1/content/articles" --header "X-Joomla-Token: $TOKEN"