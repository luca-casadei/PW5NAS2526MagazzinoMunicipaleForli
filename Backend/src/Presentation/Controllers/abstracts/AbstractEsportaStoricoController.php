<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers\abstracts;

use Backend\Application\interfaces\serv\IEsportaStorico;
use Backend\Presentation\Response;
use Exception;

abstract class AbstractEsportaStoricoController {
    protected IEsportaStorico $esportaStoricoService;

    public function __construct(IEsportaStorico $esportaStoricoService) 
    {
        $this->esportaStoricoService = $esportaStoricoService;
    }
    public function esporta(): void {
        try {
            // recupero dati
            $logGrezziDto = $this->esportaStoricoService->execute();

            if (empty($logGrezziDto)) {
                $resp = new Response("error", "Nessun movimento trovato nell'ultimo anno.", 404);
                $this->json_response($resp, $resp->get_code());
                return;
            }
            //logica specifica dei "figli"
            $this->formatAndOutput($logGrezziDto);

        } catch (Exception $e) {
            // 3. Logica condivisa: Gestione centralizzata degli errori
            $statusCode = $e->getCode() ?: 500;
            $statusCode = ($statusCode >= 100 && $statusCode < 600) ? $statusCode : 500; 

            $resp = new Response("error", "Errore durante l'export: " . $e->getMessage(), $statusCode);
            $this->json_response($resp, $resp->get_code());
            return;
        }
    }

    //contratto da implementare
    abstract protected function formatAndOutput(array $logGrezziDto): void;

    /**
     * Helper condiviso per le risposte JSON
     */
    protected function json_response(Response $resp, int $status = 200): void
    {
        header("Content-Type: application/json", true, $status);
        echo json_encode($resp);
        exit();
    }
}