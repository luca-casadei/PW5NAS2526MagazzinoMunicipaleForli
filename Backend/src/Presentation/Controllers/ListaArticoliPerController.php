<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;

use Backend\Application\interfaces\serv\IGetTipologiaById;
use Backend\Application\interfaces\serv\ITrovaArticoliByNome;
use Backend\Application\interfaces\serv\IVediScaffali;
use Backend\Presentation\dtos\ResponseArticoli;
use Backend\Presentation\Response;

// Estendiamo la classe astratta invece di crearla da zero
class ListaArticoliPerController {
    private ITrovaArticoliByNome $artNomeService;
    private IVediScaffali $artScaffaliService;
    private IGetTipologiaById $getTipologiaService;
    public function __construct(ITrovaArticoliByNome $artNomeService, IVediScaffali $artScaffaliService, IGetTipologiaById $getTipologiaService) {
        $this->artNomeService = $artNomeService;
        $this->artScaffaliService = $artScaffaliService;
        $this->getTipologiaService = $getTipologiaService;
    }
    public function get_articoli_per_nome() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $articoliCompleti = $this->artNomeService->getArticoliByNome($input['nomeArt']);
            $articoliResponse = [];
            foreach ($articoliCompleti as $articolo){
                $tipologia = $this->getTipologiaService->execute($articolo->idTipologia);
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
    public function get_articoli_per_scaffale() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        try {
            $articoliCompleti = $this->artScaffaliService->getContenutoById($input['idArmadio'], $input['numScaffale']);
            $articoliResponse = [];
            foreach ($articoliCompleti as $articolo){
                $tipologia = $this->getTipologiaService->execute($articolo->idTipologia);
                $articoliResponse[] = new ResponseArticoli(
                    $articolo->idArticolo,
                    $articolo->nomeArticolo,
                    $tipologia->nome->nome,
                    $articolo->attributi,
                    $articolo->quantitaTotale
                );
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