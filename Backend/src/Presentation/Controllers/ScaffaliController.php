<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\commands\CreateScaffaleDTO;
use Backend\Application\commands\DeleteScaffaleDTO;
use Backend\Application\dtos\ReadArmadioDTO;
use Backend\Application\interfaces\serv\ICreaScaffale;
use Backend\Application\interfaces\serv\IEliminaScaffale;
use Backend\Application\interfaces\serv\IGetAllScaffali;
use Backend\Application\interfaces\serv\IGetScaffaliArmadioById;
use Backend\Presentation\Response;
use Backend\Presentation\mapper\PresentationMapper;
use ErrorException;

class ScaffaliController {
    private ICreaScaffale $creaScaffaleService;
    private IGetScaffaliArmadioById $vediScaffaliOfArmadioService;
    private IGetAllScaffali $getAllScaffaliService;
    private IEliminaScaffale $eliminaScaffaleService;

    public function __construct(ICreaScaffale $creaScaffaleService, IGetAllScaffali $getAllScaffaliService, IGetScaffaliArmadioById $vediScaffaliOfArmadioService, IEliminaScaffale $eliminaScaffaleService) {
        $this->creaScaffaleService = $creaScaffaleService;
        $this->getAllScaffaliService = $getAllScaffaliService;
        $this->vediScaffaliOfArmadioService = $vediScaffaliOfArmadioService;
        $this->eliminaScaffaleService = $eliminaScaffaleService;
    }
    public function get_scaffali_of_armadio(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $armadioDTO = new ReadArmadioDTO($input['armadioId']);
            if($armadioDTO->id <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $scaffaliEntities = $this->vediScaffaliOfArmadioService->execute($armadioDTO);
            $scaffali = [];
            foreach($scaffaliEntities as $scaffale){
                $scaffali[] = PresentationMapper::scaffale_to_ResponseScaffale($scaffale);
            }
            $resp = new Response("success", "Scaffali dell'armadio ottenuti correttamente!", 200, $scaffali);
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
            $scaffaliEntities = $this->getAllScaffaliService->execute();
            $scaffali = [];
            foreach($scaffaliEntities as $scaffale){
                $scaffali[] = PresentationMapper::scaffale_to_ResponseScaffale($scaffale);
            }
            $resp = new Response("success", "Scaffali ottenuti correttamente!", 200, $scaffali);
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
        $createScaffale = new CreateScaffaleDTO(
            $input['armadioId']
        );
        
        try {
            if($createScaffale->armadioId <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $this->creaScaffaleService->execute($createScaffale);
            $resp = new Response("success","Scaffale creato", 201);
            $this->json_response($resp, $resp->get_code());

        } catch (\Exception $e) {
            // Se il Service fallisce, catturiamo l'errore qui
            $resp = new Response("error", $e->getMessage(), 500);
            $this->json_response($resp, $resp->get_code());
            return;
        }
    }
    public function elimina_scaffale(){
        $input = json_decode(file_get_contents('php://input'), true);
        // Creiamo il DTO senza descrizione
        $deleteScaffale = new DeleteScaffaleDTO(
            $input['armadioId'],
            $input['numScaffale']
        );
        
        try {
            if($deleteScaffale->armadioId <= 0 || $deleteScaffale->numScaffale <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $this->eliminaScaffaleService->eliminaById($deleteScaffale);
            $resp = new Response("success","Scaffale eliminato", 200);
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

