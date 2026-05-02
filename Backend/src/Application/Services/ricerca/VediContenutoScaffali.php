<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\dtos\ReadScaffaleDTO;
use Backend\Application\interfaces\repo\IGetQtUsataArticoloRepo;
use Backend\Application\interfaces\repo\IVediArticoliPerScaffaleRepo;
use Backend\Application\interfaces\serv\IVediScaffali;
use Backend\Application\mappers\ReadMapper;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Application\response\ResponseArticoloQuantitaDTO;
use Backend\Application\response\ResponseAttributoConValoreDTO;

class VediContenutoScaffali implements IVediScaffali{
    private IVediArticoliPerScaffaleRepo $articoliRepo;
    private IVediTipologieRepo $tipologiaRepo;
    private IGetAttributiByArtIdRepo $attributiRepo;
    private IGetValoreAttributoRepo $valoreAttributoRepo;
    private IGetQtArticoloRepo $quantitaRepo;
    private IGetQtUsataArticoloRepo $quantitaUsataRepo;

    public function __construct(
        IVediArticoliPerScaffaleRepo $articoliRepo,
        IVediTipologieRepo $tipologiaRepo,
        IGetAttributiByArtIdRepo $attributiRepo,
        IGetValoreAttributoRepo $valoreAttributoRepo,
        IGetQtArticoloRepo $quantitaRepo, 
        IGetQtUsataArticoloRepo $quantitaUsataRepo
    ) {
        $this->articoliRepo = $articoliRepo;
        $this->tipologiaRepo = $tipologiaRepo;
        $this->attributiRepo = $attributiRepo;
        $this->valoreAttributoRepo = $valoreAttributoRepo;
        $this->quantitaRepo = $quantitaRepo;
        $this->quantitaUsataRepo = $quantitaUsataRepo;
    }
    public function getContenutoById(ReadScaffaleDTO $readScaffale): array
    {
        $readScaffale = new ReadScaffaleDTO($readScaffale->armadioId, $readScaffale->numeroScaffale);
        $scaffale = ReadMapper::DTO_To_Scaffale($readScaffale);
        $articoliBase = $this->articoliRepo->getArticoli($scaffale);

        $articoliCompleti = [];

        foreach ($articoliBase as $articolo) {
            $readArt = ReadMapper::Articolo_To_DTO($articolo);
            $articoloId = $readArt->id;

            $tipologia = $this->tipologiaRepo->getTipologiaByArticoloId($articoloId);
            $readTipologia = ReadMapper::Tipologia_To_DTO($tipologia);

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

            $quantita = $this->quantitaRepo->getQuantita($articoloId, $readScaffale->numeroScaffale, $readScaffale->armadioId);
            $quantitaUsata = $this->quantitaUsataRepo->getQuantita($articoloId, $readScaffale->numeroScaffale, $readScaffale->armadioId);
            if($quantita === null) {
                $quantita = 0; // o un DTO con quantità 0, a seconda di come è strutturato
            }
            if($quantitaUsata === null) {
                $quantitaUsata = 0; // o un DTO con quantità 0, a seconda di come è strutturato
            }

            $articoliCompleti[] = new ResponseArticoloQuantitaDTO(
                $articoloId,
                $readArt->nome,
                $readTipologia->id,
                $attributiConValore,
                $quantita,
                $quantitaUsata
            );
        }
        return $articoliCompleti;
    }
}