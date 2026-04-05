<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\interfaces\serv\ICreaScaffale;
use Backend\Application\interfaces\serv\IGetAllScaffali;
use Backend\Application\interfaces\serv\IGetScaffaliArmadioById;
use Backend\Presentation\Response;
use Backend\Presentation\mapper\PresentationMapper;

class ScaffaliController {
    private ICreaScaffale $creaScaffaleService;
    private IGetScaffaliArmadioById $vediScaffaliOfArmadioService;
    private IGetAllScaffali $getAllScaffaliService;

    public function __construct(ICreaScaffale $creaScaffaleService, IGetAllScaffali $getAllScaffaliService, IGetScaffaliArmadioById $vediScaffaliOfArmadioService) {
        $this->creaScaffaleService = $creaScaffaleService;
        $this->getAllScaffaliService = $getAllScaffaliService;
        $this->vediScaffaliOfArmadioService = $vediScaffaliOfArmadioService;
    }
    public function get_scaffale_of_armadio(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $this->eliminaTipoService->eliminaByNome($input['nome']);
            $resp = new Response("success", "Tipologia eliminata", 200);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function get_all(){
        try{
            $tipologieEntities = $this->vediTipoService->execute();
            $tipologie = [];
            foreach($tipologieEntities as $tipologia){
                $tipologie[] = PresentationMapper::tipologia_to_ResponseTipologia($tipologia);
            }
            $resp = new Response("success", "Tipologie ottenute correttamente!", 200, $tipologie);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    
    public function crea_scaffale() {
        $input = json_decode(file_get_contents('php://input'), true);
        // Creiamo il DTO senza descrizione
        $createTipologia = new CreateTipologiaDTO(
            $input['nome'], ""
        );
        
        try {
            $this->creaTipoService->execute($createTipologia);
            $resp = new Response("success","Tipologia creata", 201);
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

