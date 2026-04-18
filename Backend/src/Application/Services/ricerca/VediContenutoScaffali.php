<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\dtos\ReadScaffaleDTO;
use Backend\Application\interfaces\repo\IVediArticoliPerScaffaleRepo;
use Backend\Application\interfaces\serv\IVediScaffali;
use Backend\Application\mappers\ReadMapper;
use Backend\Application\response\ResponseArticoloCompletoDTO;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Application\response\ResponseAttributoConValoreDTO;

class VediContenutoScaffali implements IVediScaffali{
    private IVediArticoliPerScaffaleRepo $articoliRepo;
    private IVediTipologieRepo $tipologiaRepo;
    private IGetAttributiByArtIdRepo $attributiRepo;
    private IGetValoreAttributoRepo $valoreAttributoRepo;
    private IGetQtArticoloRepo $quantitaRepo;

    public function __construct(
        IVediArticoliPerScaffaleRepo $articoliRepo,
        IVediTipologieRepo $tipologiaRepo,
        IGetAttributiByArtIdRepo $attributiRepo,
        IGetValoreAttributoRepo $valoreAttributoRepo,
        IGetQtArticoloRepo $quantitaRepo
    ) {
        $this->articoliRepo = $articoliRepo;
        $this->tipologiaRepo = $tipologiaRepo;
        $this->attributiRepo = $attributiRepo;
        $this->valoreAttributoRepo = $valoreAttributoRepo;
        $this->quantitaRepo = $quantitaRepo;
    }
    public function getContenutoById(ReadScaffaleDTO $readScaffale): array
    {
        $readScaffale = new ReadScaffaleDTO($readScaffale->armadioId, $readScaffale->numeroScaffale);
        $scaffale = ReadMapper::DTO_To_Scaffale($readScaffale);
        // 1. Recupero della lista base di tutti gli articoli tramite Repository
        $articoliBase = $this->articoliRepo->getArticoli($scaffale);

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
            $quantitaTotaleDto = $this->quantitaRepo->getQuantita($articoloId, $readScaffale->numeroScaffale, $readScaffale->armadioId);
            if($quantitaTotaleDto === null) {
                $quantitaTotaleDto = 0; // o un DTO con quantità 0, a seconda di come è strutturato
            }

            // d. Aggregazione di tutte le informazioni in un singolo Model (DTO)
            $articoliCompleti[] = new ResponseArticoloCompletoDTO(
                $articoloId,
                $readArt->nome,
                $readTipologia->id,
                $attributiConValore,
                $quantitaTotaleDto
            );
        }
        return $articoliCompleti;
    }
}