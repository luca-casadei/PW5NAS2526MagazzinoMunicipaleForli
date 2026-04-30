<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\commands\GetTipologiaDTO;
use Backend\Application\dtos\ReadQuantitaDTO;
use Backend\Application\interfaces\serv\IGetTipologiaById;
use Backend\Application\interfaces\serv\IVediArticoli;
use Backend\Application\interfaces\serv\IVediArticoliQtBasse;
use Backend\Presentation\dtos\ResponseArticoli;
use Backend\Presentation\Response;
use ErrorException;

// Estendiamo la classe astratta invece di crearla da zero
class ListaArticoliController {
    private IVediArticoli $articoliService;
    private IGetTipologiaById $getTipologiaService;
    private IVediArticoliQtBasse $articoliQtBasseService;
    public function __construct(IVediArticoli $articoliService, IGetTipologiaById $getTipologiaService, IVediArticoliQtBasse $articoliQtBasseService) {
        $this->articoliService = $articoliService;
        $this->getTipologiaService = $getTipologiaService;
        $this->articoliQtBasseService = $articoliQtBasseService;
    }
    public function get_articoli() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $articoliCompleti = $this->articoliService->execute();
            $articoliResponse = [];
            foreach ($articoliCompleti as $articolo){
                $dtoTipo = new GetTipologiaDTO($articolo->idTipologia);
                $tipologia = $this->getTipologiaService->execute($dtoTipo);
                $articoliResponse[] = new ResponseArticoli(
                    $articolo->idArticolo,
                    $articolo->nomeArticolo,
                    $tipologia->nome->nome,
                    $articolo->attributi,
                    $articolo->quantitaTotale
                );
            }
            $resp = new Response("success","Articoli trovati", 200, $articoliResponse);
            $this->json_response($resp, $resp->get_code());

        } catch (\Exception $e) {
            // Se il Service fallisce, catturiamo l'errore qui
            $resp = new Response("error", $e->getMessage(), 500);
            $this->json_response($resp, $resp->get_code());
            return;
        }
    }
    public function get_articoli_qt_bassa() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $quantitaMinima = new ReadQuantitaDTO((int)$input['qtMinima']);
            if($quantitaMinima->quantita <= 0)
                throw new ErrorException("Inserisci dati validi", 400);
            $articoliCompleti = $this->articoliQtBasseService->execute($quantitaMinima);
            $articoliResponse = [];
            foreach ($articoliCompleti as $articolo){
                $dtoTipo = new GetTipologiaDTO($articolo->idTipologia);
                $tipologia = $this->getTipologiaService->execute($dtoTipo);
                $articoliResponse[] = new ResponseArticoli(
                    $articolo->idArticolo,
                    $articolo->nomeArticolo,
                    $tipologia->nome->nome,
                    $articolo->attributi,
                    $articolo->quantitaTotale
                );
            }
            if (empty($articoliResponse)) {
                $resp = new Response("success","Nessun articolo con quantità basse trovato", 200, []);
                $this->json_response($resp, $resp->get_code());
            }
            $resp = new Response("success","Articoli con quantita basse trovati", 200, $articoliResponse);
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