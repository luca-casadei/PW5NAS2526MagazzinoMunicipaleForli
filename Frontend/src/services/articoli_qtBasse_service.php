<?php
$dati = file_get_contents('php://input');

if (!$dati) {
    http_response_code(400);
    echo json_encode(["error" => "Nessun dato ricevuto"]);
    exit;
}
$ch = curl_init("http://backend/articoli/carenti");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $dati);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$risposta = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

http_response_code($http_status);
header('Content-Type: application/json');
echo $risposta;
?>