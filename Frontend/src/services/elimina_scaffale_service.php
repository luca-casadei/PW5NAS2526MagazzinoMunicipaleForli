<?php
$inputJSON = file_get_contents('php://input');
$ch = curl_init("http://backend/scaffali");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Diciamo a cURL di usare il metodo DELETE (nessun body necessario)
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

if ($inputJSON) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($inputJSON)
    ]);
}
$risposta = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

http_response_code($http_status);
header('Content-Type: application/json');
echo $risposta;
?>