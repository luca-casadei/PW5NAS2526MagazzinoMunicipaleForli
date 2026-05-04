<?php
declare(strict_types=1);
namespace Backend\Application\Services\magazzino;

use Backend\Application\commands\UpdateDimQuantitaDTO;
use Backend\Application\interfaces\repo\IAggiornaQtArticoloRepo;
use Backend\Application\interfaces\repo\ICreaPresenzaRepo;
use Backend\Application\interfaces\repo\IEliminaPresenzaRepo;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
use Backend\Application\interfaces\repo\IGetQtUsataArticoloRepo;
use Backend\Application\interfaces\repo\IVerificaPresenzaRepo;
use Backend\Application\interfaces\serv\IDimQtArticolo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaUsata;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Exception;

class DimQtArticolo implements IDimQtArticolo{
    private IAggiornaQtArticoloRepo $repoDim;
    private IGetQtArticoloRepo $repoQt;
    private IGetQtUsataArticoloRepo $repoQtUsato;
    private IEliminaPresenzaRepo $repoElimina;
    public function __construct(IAggiornaQtArticoloRepo $repository, IGetQtArticoloRepo $repoQt,
    IGetQtUsataArticoloRepo $repoQtUsato, IEliminaPresenzaRepo $repoElimina){
        $this->repoDim = $repository;
        $this->repoQt = $repoQt;
        $this->repoQtUsato = $repoQtUsato;
        $this->repoElimina = $repoElimina;
    }
    public function execute(UpdateDimQuantitaDTO $update): void {
        $quantitaAttuale = $this->repoQt->getQuantita($update->articoloId, $update->numScaffale, $update->armadioId);
        $quantitaUsato = $this->repoQtUsato->getQuantita($update->articoloId, $update->numScaffale, $update->armadioId);

        if ($quantitaAttuale === null && $quantitaUsato === null) {
            throw new Exception("L'articolo con ID $update->articoloId non è presente nello scaffale $update->numScaffale dell'armadio $update->armadioId.");
        }

        if ($quantitaAttuale === null) $quantitaAttuale = 0;
        
        $nuovaQuantitaTotale = $quantitaAttuale - $update->quantitaDim;
        if ($nuovaQuantitaTotale < 0) {
            throw new Exception("Non è possibile togliere questa quantità");
        }

        if($nuovaQuantitaTotale == 0 && $quantitaUsato == 0){
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
            new QuantitaScorta($nuovaQuantitaTotale),
            new QuantitaUsata(0)
        );
        $this->repoDim->updateQuantita($quantitaAggiornataDto);
    }
}
