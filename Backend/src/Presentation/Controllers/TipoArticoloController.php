<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\commands\CreateAttributoDTO;
use Backend\Application\commands\CreateTipoArticoloDTO;
use Backend\Application\commands\DeleteTipoArticoloDTO;
use Backend\Application\interfaces\serv\ICreaTipoArticolo;
use Backend\Application\interfaces\serv\IEliminaTipoArticolo;
use Backend\Presentation\Response;

class TipoArticoloController {
    private ICreaTipoArticolo $creaTipoArticoloService;
    private IEliminaTipoArticolo $eliminaTipoArticoloService;

    public function __construct(ICreaTipoArticolo $creaTipoArticoloService, IEliminaTipoArticolo $eliminaTipoArticoloService) {
        $this->creaTipoArticoloService = $creaTipoArticoloService;
        $this->eliminaTipoArticoloService = $eliminaTipoArticoloService;
    }
    public function elimina_tipoArticolo(){
        $input = json_decode(file_get_contents('php://input'), true);
        try{
            $eliminaTipo = new DeleteTipoArticoloDTO($input['articoloId']);
            $this->eliminaTipoArticoloService->execute($eliminaTipo);
            $resp = new Response("success", "Articolo eliminato", 200);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    
    public function crea_tipoArticolo() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['tipologiaId'], $input['nome'], $input['attributi']) || !is_array($input['attributi'])) {
                $resp = new Response("error", "Dati mancanti o malformati", 400);
                $this->json_response($resp, $resp->get_code());
                return;
            }

            $attributiOggetti = [];
            foreach ($input['attributi'] as $attrArray) {
                // Costruiamo l'oggetto DTO per ogni attributo
                $attributiOggetti[] = new CreateAttributoDTO($attrArray['nome'], $attrArray['valore']);
            }

            // 3. Imballiamo tutto nel Command DTO principale
            $command = new CreateTipoArticoloDTO(
                (int)$input['tipologiaId'],
                $input['nome'],
                $attributiOggetti
            );
            $this->creaTipoArticoloService->execute($command);
            $resp = new Response("success","Articolo creato", 201);
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

