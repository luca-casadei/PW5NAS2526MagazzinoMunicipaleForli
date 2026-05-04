<?php
declare(strict_types=1);
namespace Backend\Application\Services\magazzino;

use Backend\Application\commands\UpdateDimQuantitaUsataDTO;
use Backend\Application\interfaces\repo\IAggiornaQtUsataArticoloRepo;
use Backend\Application\interfaces\repo\IEliminaPresenzaRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetQtUsataArticoloRepo;
use Backend\Application\interfaces\serv\IDimQtUsataArticolo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaUsata;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Exception;

class DimQtUsataArticolo implements IDimQtUsataArticolo{
    private IAggiornaQtUsataArticoloRepo $repoDim;
    private IGetQtUsataArticoloRepo $repoQtUsato;
    private IGetQtArticoloRepo $repoQt;
    private IEliminaPresenzaRepo $repoElimina;
    public function __construct(IAggiornaQtUsataArticoloRepo $repository, IGetQtUsataArticoloRepo $repoQtUsato,
    IGetQtArticoloRepo $repoQt, IEliminaPresenzaRepo $repoElimina){
        $this->repoDim = $repository;
        $this->repoQtUsato = $repoQtUsato;
        $this->repoQt = $repoQt;
        $this->repoElimina = $repoElimina;
    }
    public function execute(UpdateDimQuantitaUsataDTO $update): void {
        $quantitaUsato = $this->repoQtUsato->getQuantita($update->articoloId, $update->numScaffale, $update->armadioId);
        $quantita = $this->repoQt->getQuantita($update->articoloId, $update->numScaffale, $update->armadioId);

        if ($quantitaUsato === null && $quantita === null) {
            throw new Exception("L'articolo con ID $update->articoloId non è presente nello scaffale $update->numScaffale dell'armadio $update->armadioId.");
        }

        if ($quantitaUsato === null) $quantitaUsato = 0;
        
        $nuovaQuantitaTotale = $quantitaUsato - $update->quantitaDim;
        if ($nuovaQuantitaTotale < 0) {
            throw new Exception("Non è possibile togliere questa quantità");
        }

        if($nuovaQuantitaTotale == 0 && $quantita == 0){
            $this->repoElimina->eliminaPresenza($update->articoloId, $update->numScaffale, $update->armadioId);
            return;
        }

        $quantitaAggiornataDto = new ArticoliInScaffali(
            new ArticoliInScaffaliId(
                new ArticoloId($update->articoloId),
                new ScaffaleId(
                    new ArmadioId($update->armadioId),
                    new NumeroScaffale($update->numScaffale)
                )
            ),
            new QuantitaScorta(0),
            new QuantitaUsata($nuovaQuantitaTotale)
        );
        $this->repoDim->updateQuantita($quantitaAggiornataDto);
    }
}
