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
            $this->formatAndOutput($logGrezziDto, false);

        } catch (Exception $e) {
            $arrayErrore = [];
            $arrayErrore['campo'] = "Errore";
            $arrayErrore['valore'] = "C'è stato un errore nella generazione del csv";
            $this->formatAndOutput($arrayErrore, true);
        }
        
    }

    //contratto da implementare
    abstract protected function formatAndOutput(array $logGrezziDto, bool $errore): void;

    /**
     * Helper condiviso per le risposte JSON
     */
    protected function json_response(Response $resp, int $status = 200): void
    {
        header("Content-Type: application/json", true, $status);
        echo json_encode($resp);
        exit();
    }
    public function method_not_allowed(){
        $resp = new Response("error", "Metodo non consentito", 405);
        $this->json_response($resp, $resp->get_code());
    }
}