<?php
$ch = curl_init("http://backend/tipologie"); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$risposta = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

http_response_code($http_status);
header('Content-Type: application/json');
echo $risposta;
?>