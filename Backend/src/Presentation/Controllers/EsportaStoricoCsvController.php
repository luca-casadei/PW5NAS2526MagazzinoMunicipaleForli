<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\interfaces\serv\IEsportaStorico;
use Exception;

class EsportaStoricoCsvController {
    private IEsportaStorico $esportaStoricoService;
    public function __construct(IEsportaStorico $esportaStoricoService) 
    {
        $this->esportaStoricoService = $esportaStoricoService;
    }

    public function esportaCsv(): void {
        try {
            $logGrezziDto = $this->esportaStoricoService->execute();

            if (empty($logGrezziDto)) {
                // Gestione opzionale se non ci sono dati
                echo "Nessun movimento trovato nell'ultimo anno.";
                return;
            }
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=storico_movimenti.csv');

            // 3. Formattazione e Output dei Dati (Presentation Logic)
            // Apriamo lo stream di output come un file (niente file su disco, solo stream)
            $output = fopen('php://output', 'w');

            // Scrittura dell'intestazione del CSV
            fputcsv($output, ['Log_Id', 'Data_Ora', 'IdArticolo', 'Scaffale', 'QtaPrecedente', 'QtaAggiornata', 'DettagliSnapshot']);

            // b. Iterazione e Scrittura dei Dati
            // Mappiamo ogni DTO parziale dell'Application in una riga CSV
            foreach ($logGrezziDto as $logDto) {
                // Costruiamo un array piatto per la riga
                fputcsv($output, [
                    $logDto->logId,
                    $logDto->dataOraModifica,
                    $logDto->articoloId,
                    "Scaffale $logDto->numeroScaffale / Armadio $logDto->armadioId",
                    $logDto->qtaPrecedente,
                    $logDto->qtaAggiornata,
                    $logDto->attributiSnapshot
                ]);
            }
            fclose($output);

        } catch (Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "Si è verificato un errore durante la generazione dell'export: " . $e->getMessage();
        }
    }
}