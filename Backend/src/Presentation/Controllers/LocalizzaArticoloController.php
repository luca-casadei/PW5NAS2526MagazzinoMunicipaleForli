<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\interfaces\serv\ILocalizzaArticolo;
use Backend\Presentation\Response;

// Estendiamo la classe astratta invece di crearla da zero
class LocalizzaArticoloController {
    private ILocalizzaArticolo $localizzaService;
    public function __construct(ILocalizzaArticolo $localizzaService) {
        $this->localizzaService = $localizzaService;
    }
    public function localizza() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $articoliInScaffali = $this->localizzaService->localizzaById($input['articoloId']);
            if(empty($articoliInScaffali)) {
                $resp = new Response("success", "Articolo non presente in nessuno scaffale", 200);
                $this->json_response($resp, $resp->get_code());
                return;
            }
            $resp = new Response("success","Articolo trovato", 200, $articoliInScaffali);
            $this->json_response($resp, $resp->get_code());

        } catch (\Exception $e) {
            // Se il Service fallisce, catturiamo l'errore qui
            $resp = new Response("error", $e->getMessage(), 500);
            $this->json_response($resp, $resp->get_code());
            return;
        }
    }
    public function method_not_allowed(){
        $resp = new Response("error", "Metodo non consentito", 405);
        $this->json_response($resp, $resp->get_code());
    }

    private function json_response(Response $resp, int $status = 200): void
    {
        header("Content-Type: application/json", true, $status);
        echo json_encode($resp);
        exit();
    }
}