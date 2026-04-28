<?php
// 1. URL della tua API reale nel backend
$url_api_backend = "http://backend/esportaStorico"; 

// 2. Inizializza cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api_backend);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$csvData = curl_exec($ch);

if(curl_errno($ch)){
    die('Errore cURL: ' . curl_error($ch));
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="storico_movimenti.csv"');

echo $csvData;
exit;
?>