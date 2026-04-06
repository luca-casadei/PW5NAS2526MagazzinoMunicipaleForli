<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\interfaces\serv\IVediScaffali;
use App\Application\response\ResponseArticoloCompletoDTO;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Application\interfaces\repo\IVediArticoliRepo;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Application\response\ResponseAttributoConValoreDTO;

class VediContenutoScaffali implements IVediScaffali{
    private IVediArticoliRepo $articoliRepo;
    private IVediTipologieRepo $tipologiaRepo;
    private IGetAttributiByArtIdRepo $attributiRepo;
    private IGetValoreAttributoRepo $valoreAttributoRepo;
    private IGetQtArticoloRepo $quantitaRepo;

    public function __construct(
        IVediArticoliRepo $articoliRepo,
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
    public function getContenutoById(int $armadioId, int $numScaffale): array
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
            foreach ($attributi as $attributo) {
                $valore = $this->valoreAttributoRepo->getValore($articoloId, $attributo['Attributo_Id']);
                $attributiConValore[] = new ResponseAttributoConValoreDTO(
                    $attributo['Attributo_Id'],
                    $attributo['Nome_Attributo'],
                    $valore
                );
            }

            // c. Recupero della quantità totale tramite Repository
            $quantitaTotaleDto = $this->quantitaRepo->getQuantita($articoloId, $numScaffale, $armadioId);

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