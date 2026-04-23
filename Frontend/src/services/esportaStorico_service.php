<?php
// 1. URL della tua API reale nel backend
$url_api_backend = "http://backend/esportaStorico"; 

// 2. Inizializza cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api_backend);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Se la tua API richiede un metodo GET, questo basta.
// Se richiede autenticazione o altri header, aggiungili qui:
/*
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer IL_TUO_TOKEN'
));
*/

// 3. Esegui la chiamata e salva il contenuto del CSV
$csvData = curl_exec($ch);

// Controlla se ci sono stati errori di connessione con cURL
if(curl_errno($ch)){
    die('Errore cURL: ' . curl_error($ch));
}

// 4. FORZA IL DOWNLOAD NEL BROWSER DELL'UTENTE
// Queste due righe dicono al browser: "Questo è un file da scaricare, non una pagina da leggere!"
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="storico_movimenti.csv"');

// 5. Stampa i dati recuperati da cURL. 
// Essendo sotto gli header di "attachment", diventeranno il contenuto del file scaricato.
echo $csvData;
exit;
?>