<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\commands\UpdateAggQuantitaDTO;
use Backend\Application\commands\UpdateDimQuantitaDTO;
use Backend\Application\interfaces\serv\IAggQtArticolo;
use Backend\Application\interfaces\serv\IDimQtArticolo;
use Backend\Presentation\Response;


class ArticoliQuantitaController {
    private IAggQtArticolo $aggQtArticoloService;
    private IDimQtArticolo $dimQtArticoloService;

    public function __construct(IAggQtArticolo $aggQtArticoloService, IDimQtArticolo $dimQtArticoloService) {
        $this->aggQtArticoloService = $aggQtArticoloService;
        $this->dimQtArticoloService = $dimQtArticoloService;
    }
    public function aggiungi(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $update = new UpdateAggQuantitaDTO(
                $input['articoloId'],
                $input['numeroScaffale'],
                $input['armadioId'],
                $input['quantita']
            );
            $this->aggQtArticoloService->execute($update);
            $resp = new Response("success", "Quantità aggiunta correttamente", 200);
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function diminuisci(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $update = new UpdateDimQuantitaDTO(
                $input['articoloId'],
                $input['numeroScaffale'],
                $input['armadioId']
            );
            $this->dimQtArticoloService->execute($update);
            $resp = new Response("success", "Quantità diminuita correttamente", 200);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
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

