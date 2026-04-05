<?php

namespace Backend\Application\Services;

use App\Application\response\ResponseArticoloCompletoDTO;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Application\interfaces\repo\IGetQtTotArticoloRepo;
use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Application\interfaces\repo\IVediArticoliRepo;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Application\response\ResponseAttributoConValoreDTO;
use Backend\Application\interfaces\serv\IVediArticoli;


class MostraTuttiArticoli implements IVediArticoli
{
    private IVediArticoliRepo $articoliRepo;
    private IVediTipologieRepo $tipologiaRepo;
    private IGetAttributiByArtIdRepo $attributiRepo;
    private IGetValoreAttributoRepo $valoreAttributoRepo;
    private IGetQtTotArticoloRepo $quantitaTotaleRepo;

    public function __construct(
        IVediArticoliRepo $articoliRepo,
        IVediTipologieRepo $tipologiaRepo,
        IGetAttributiByArtIdRepo $attributiRepo,
        IGetValoreAttributoRepo $valoreAttributoRepo,
        IGetQtTotArticoloRepo $quantitaTotaleRepo
    ) {
        $this->articoliRepo = $articoliRepo;
        $this->tipologiaRepo = $tipologiaRepo;
        $this->attributiRepo = $attributiRepo;
        $this->valoreAttributoRepo = $valoreAttributoRepo;
        $this->quantitaTotaleRepo = $quantitaTotaleRepo;
    }
    public function execute(): array
    {
        // 1. Recupero della lista base di tutti gli articoli tramite Repository
        $articoliBase = $this->articoliRepo->getAllArticoli();

        $articoliCompleti = [];

        // 2. Per ogni articolo, raccogliamo tutte le informazioni necessarie
        foreach ($articoliBase as $articolo) {
            $articoloId = $articolo['Articolo_Id'];

            // a. Recupero della tipologia tramite Repository
            $tipologia = $this->tipologiaRepo->getTipologiaByArticoloId($articoloId);

            // b. Recupero degli attributi e valori tramite Repository
            $attributiConValore = [];
            $attributi = $this->attributiRepo->getAttributiByArticoloId($articoloId);
            foreach ($attributi as &$attributo) {
                $valore = $this->valoreAttributoRepo->getValore($articoloId, $attributo['Attributo_Id']);
                $attributiConValore[] = new ResponseAttributoConValoreDTO(
                    $attributo['Attributo_Id'],
                    $attributo['Nome_Attributo'],
                    $valore
                );
            }

            // c. Recupero della quantità totale tramite Repository
            $quantitaTotaleDto = $this->quantitaTotaleRepo->getQuantita($articoloId);

            // d. Aggregazione di tutte le informazioni in un singolo Model (DTO)
            $articoliCompleti[] = new ResponseArticoloCompletoDTO(
                $articoloId,
                $articolo['Nome'],
                $tipologia['Tipologia_Id'],
                $attributiConValore,
                $quantitaTotaleDto
            );
        }
        return $articoliCompleti;
    }
}