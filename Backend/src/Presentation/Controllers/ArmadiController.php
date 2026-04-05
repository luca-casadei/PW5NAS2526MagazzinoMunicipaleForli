<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\interfaces\serv\ICreaArmadio;
use Backend\Application\interfaces\serv\IVediArmadi;
use Backend\Presentation\mapper\PresentationMapper;
use Backend\Presentation\Response;

class ArmadiController {
    private ICreaArmadio $creaArmadioService;
    private IVediArmadi $vediArmadiService;

    public function __construct(ICreaArmadio $creaArmadioService, IVediArmadi $vediArmadiService) {
        $this->creaArmadioService = $creaArmadioService;
        $this->vediArmadiService = $vediArmadiService;
    }
    public function get_armadi(){
        try{
            $armadiEntities = $this->vediArmadiService->execute();
            $armadi = [];
            foreach($armadiEntities as $armadio){
                $armadi[] = PresentationMapper::armadio_to_ResponseArmadio($armadio);
            }
            $resp = new Response("success", "Armadi ottenuti correttamente!", 200, $armadi);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    
    public function crea_armadio() {
        try {
            $this->creaArmadioService->execute();
            $resp = new Response("success","Armadio creato", 201);
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

