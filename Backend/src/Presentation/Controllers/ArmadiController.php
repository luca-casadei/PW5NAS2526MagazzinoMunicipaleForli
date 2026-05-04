<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\commands\DeleteArmadioDTO;
use Backend\Application\interfaces\serv\ICreaArmadio;
use Backend\Application\interfaces\serv\IEliminaArmadio;
use Backend\Application\interfaces\serv\IVediArmadi;
use Backend\Presentation\mapper\PresentationMapper;
use Backend\Presentation\Response;
use ErrorException;

class ArmadiController {
    private ICreaArmadio $creaArmadioService;
    private IVediArmadi $vediArmadiService;
    private IEliminaArmadio $eliminaArmadioService;

    public function __construct(ICreaArmadio $creaArmadioService, IVediArmadi $vediArmadiService, IEliminaArmadio $eliminaArmadioService) {
        $this->creaArmadioService = $creaArmadioService;
        $this->vediArmadiService = $vediArmadiService;
        $this->eliminaArmadioService = $eliminaArmadioService;
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
    public function elimina_armadio(){
        $input = json_decode(file_get_contents('php://input'), true);
        // Creiamo il DTO senza descrizione
        $deleteArmadio = new DeleteArmadioDTO(
            $input['armadioId'],
        );
        
        try {
            if($deleteArmadio->id <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $this->eliminaArmadioService->eliminaById($deleteArmadio);
            $resp = new Response("success","Armadio eliminato", 200);
            $this->json_response($resp, $resp->get_code());

        } catch (\Exception $e) {
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

