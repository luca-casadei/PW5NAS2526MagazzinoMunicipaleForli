<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IAggiornaQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\serv\IAggQtArticolo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Exception;

class AggQtArticolo implements IAggQtArticolo{
    private IAggiornaQtArticoloRepo $repoAgg;
    private IGetQtArticoloRepo $repoQt;
    public function __construct(IAggiornaQtArticoloRepo $repository, IGetQtArticoloRepo $repoQt){
        $this->repoAgg = $repository;
        $this->repoQt = $repoQt;
    }
    public function execute(int $articoloId, int $numeroScaffale, int $armadioId, int $quantitaDaAggiungere): void {
        // 1. Validazione della logica di business
        if ($quantitaDaAggiungere <= 0) {
            throw new Exception("La quantità da aggiungere deve essere positiva.");
        }

        // 2. Recupero della quantità attuale tramite Repository
        $quantitaAttuale = $this->repoQt->getQuantita($articoloId, $numeroScaffale, $armadioId);

        if (!$quantitaAttuale) {
            // Gestione dell'errore se l'associazione articolo-scaffale non esiste
            throw new Exception("L'articolo con ID $articoloId non è presente nello scaffale $numeroScaffale dell'armadio $armadioId.");
        }

        // 3. Calcolo della nuova quantità
        $nuovaQuantitaTotale = $quantitaAttuale + $quantitaDaAggiungere;

        // 4. Preparazione del DTO per l'aggiornamento
        $quantitaAggiornataDto = new ArticoliInScaffali(
            new ArticoliInScaffaliId(
                new ArticoloId($articoloId),
                new ScaffaleId(
                    new ArmadioId($armadioId),
                    new NumeroScaffale($numeroScaffale)
                )
            ),
            new QuantitaScorta($nuovaQuantitaTotale)
        );
        // 5. Aggiornamento sul DB tramite Repository
        // Restituisce il Model simulato aggiornato
        return $this->repoAgg->updateQuantita($quantitaAggiornataDto);
    }
}
