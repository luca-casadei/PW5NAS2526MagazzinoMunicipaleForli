<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\commands\CreateTipologiaDTO;
use Backend\Application\commands\DeleteTipologiaDTO;
use Backend\Application\interfaces\serv\ICreaTipologia;
use Backend\Application\interfaces\serv\IEliminaTipologia;
use Backend\Application\interfaces\serv\IVediTipologie;
use Backend\Presentation\Response;
use Backend\Presentation\mapper\PresentationMapper;

class TipologieController {
    private ICreaTipologia $creaTipoService;
    private IEliminaTipologia $eliminaTipoService;
    private IVediTipologie $vediTipoService;

    public function __construct(ICreaTipologia $creaTipoService, IEliminaTipologia $eliminaTipoService, IVediTipologie $vediTipoService) {
        $this->creaTipoService = $creaTipoService;
        $this->eliminaTipoService = $eliminaTipoService;
        $this->vediTipoService = $vediTipoService;
    }
    public function elimina_tipologia(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $eliminaTipo = new DeleteTipologiaDTO($input['nome']);
            $this->eliminaTipoService->eliminaByNome($eliminaTipo);
            $resp = new Response("success", "Tipologia eliminata", 200);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function get_tipologie(){
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
    
    public function crea_tipologia() {
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

