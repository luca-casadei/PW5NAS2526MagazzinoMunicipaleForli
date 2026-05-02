<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\commands\UpdateAggQuantitaUsataDTO;
use Backend\Application\commands\UpdateDimQuantitaUsataDTO;
use Backend\Application\interfaces\serv\IAggQtUsataArticolo;
use Backend\Application\interfaces\serv\IDimQtUsataArticolo;
use Backend\Presentation\Response;
use ErrorException;


class ArticoliQuantitaUsataController {
    private IAggQtUsataArticolo $aggQtArticoloService;
    private IDimQtUsataArticolo $dimQtArticoloService;

    public function __construct(IAggQtUsataArticolo $aggQtArticoloService, IDimQtUsataArticolo $dimQtArticoloService) {
        $this->aggQtArticoloService = $aggQtArticoloService;
        $this->dimQtArticoloService = $dimQtArticoloService;
    }
    public function aggiungi(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $update = new UpdateAggQuantitaUsataDTO(
                $input['articoloId'],
                $input['numeroScaffale'],
                $input['armadioId'],
                $input['quantita']
            );
            if($update->articoloId <= 0 || $update->numScaffale <= 0 || $update->armadioId <= 0 || $update->quantitaAgg <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $this->aggQtArticoloService->execute($update);
            $resp = new Response("success", "Quantità aggiunta correttamente", 200);
            $this->json_response($resp, $resp->get_code());
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
            $update = new UpdateDimQuantitaUsataDTO(
                $input['articoloId'],
                $input['numeroScaffale'],
                $input['armadioId'],
                $input['quantita']
            );
            if($update->articoloId <= 0 || $update->numScaffale <= 0 || $update->armadioId <= 0 || $update->quantitaDim <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
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

