<?php
require_once __DIR__ . '/../Session.php';
$session = new Session();

if (!$session->is_logged_in()) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Utente non autenticato nel frontend"]);
    exit;
}

$ch = curl_init("http://backend/myClasses"); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());

$risposta = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

http_response_code($http_status);
header('Content-Type: application/json');
echo $risposta;
?>