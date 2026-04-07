<?php

namespace Backend\Application\Services\magazzino;

use Backend\Application\mappers\ReadMapper;
use Backend\Application\response\ResponseArticoloCompletoDTO;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Application\interfaces\repo\IGetQtTotArticoloRepo;
use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Application\interfaces\repo\IVediArticoliRepo;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Application\response\ResponseAttributoConValoreDTO;
use Backend\Application\interfaces\serv\IVediArticoliQtBasse;


class MostraTuttiArticoliQtBasse implements IvediArticoliQtBasse
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
    public function execute($quantitaMinima): array
    {
        // 1. Recupero della lista base di tutti gli articoli tramite Repository
        $articoliBase = $this->articoliRepo->getAllArticoli();

        $articoliCompleti = [];

        // 2. Per ogni articolo, raccogliamo tutte le informazioni necessarie
        foreach ($articoliBase as $articolo) {
            $readArt = ReadMapper::Articolo_To_DTO($articolo);
            $articoloId = $readArt->id;

            // a. Recupero della tipologia tramite Repository
            $tipologia = $this->tipologiaRepo->getTipologiaByArticoloId($articoloId);
            $readTipologia = ReadMapper::Tipologia_To_DTO($tipologia);

            // b. Recupero degli attributi e valori tramite Repository
            $attributiConValore = [];
            $attributi = $this->attributiRepo->getAttributiByArticoloId($articoloId);
            foreach ($attributi as $attributo) {
                $readAttributo = ReadMapper::Attributo_To_DTO($attributo);
                $valore = $this->valoreAttributoRepo->getValore($articoloId, $readAttributo->id);
                $attributiConValore[] = new ResponseAttributoConValoreDTO(
                    $readAttributo->id,
                    $readAttributo->nome,
                    $valore
                );
            }

            // c. Recupero della quantità totale tramite Repository
            $quantitaTotaleDto = $this->quantitaTotaleRepo->getQuantita($articoloId);
            if($quantitaTotaleDto === null) {
                $quantitaTotaleDto = 0; // o un DTO con quantità 0, a seconda di come è strutturato
            }

            if($quantitaTotaleDto <= $quantitaMinima){
                $articoliCompleti[] = new ResponseArticoloCompletoDTO(
                    $articoloId,
                    $readArt->nome,
                    $readTipologia->id,
                    $attributiConValore,
                    $quantitaTotaleDto
                );
            }            
        }
        return $articoliCompleti;
    }
}


