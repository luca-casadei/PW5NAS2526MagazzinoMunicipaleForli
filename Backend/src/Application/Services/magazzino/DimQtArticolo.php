<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IAggiornaQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\serv\IDimQtArticolo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Exception;

class DimQtArticolo implements IDimQtArticolo{
    private IAggiornaQtArticoloRepo $repoDim;
    private IGetQtArticoloRepo $repoQt;
    public function __construct(IAggiornaQtArticoloRepo $repository, IGetQtArticoloRepo $repoQt){
        $this->repoDim = $repository;
        $this->repoQt = $repoQt;
    }
    public function execute(int $articoloId, int $numeroScaffale, int $armadioId): void {
        // 2. Recupero della quantità attuale tramite Repository
        $quantitaAttuale = $this->repoQt->getQuantita($articoloId, $numeroScaffale, $armadioId);

        if (!$quantitaAttuale) {
            // Gestione dell'errore se l'associazione articolo-scaffale non esiste
            throw new Exception("L'articolo con ID $articoloId non è presente nello scaffale $numeroScaffale dell'armadio $armadioId.");
        }
        if ($quantitaAttuale == 0) {
            throw new Exception("Non è possibile togliere altre quantità");
        }

        // 3. Calcolo della nuova quantità
        $nuovaQuantitaTotale = $quantitaAttuale - 1;

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
        return $this->repoDim->updateQuantita($quantitaAggiornataDto);
    }
}
