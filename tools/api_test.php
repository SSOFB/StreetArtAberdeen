<?php
/*

curl -X GET http://streetartaberdeen.com/api/index.php/v1/content/articles -H "Authorization: Bearer c2hhMjU2Ojg3MzoxNjhmNTYxNzQwMDE5Y2ExYjYxN2Q2MzhjYjEzNDA3MTMyNzFkYjRjNWJjMDc3N2JiYWI2NjUwZWNlNTRhMTAz"

{"errors":{"code":500,"title":"Internal server error"}}

https://www.ezone.co.uk/flutter/api-joomla-flutter.html

Add the required headers, for Joomla this required... 
Content-type: application/json 
Accept: application/vnd.api+json,
*/


require_once("secret.php");


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://streetartaberdeen.com/api/index.php/v1/content/articles',
  CURLOPT_RETURNTRANSFER => true,
  #CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Accept: application/vnd.api+json",
    "Authorization: Bearer " . $token
  ),
));


$response = curl_exec($curl);

curl_close($curl);
echo $response;
