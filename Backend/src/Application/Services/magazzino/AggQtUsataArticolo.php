<?php
declare(strict_types=1);
namespace Backend\Application\Services\magazzino;

use Backend\Application\commands\UpdateAggQuantitaUsataDTO;
use Backend\Application\interfaces\repo\IAggiornaQtUsataArticoloRepo;
use Backend\Application\interfaces\repo\ICreaPresenzaRepo;
use Backend\Application\interfaces\repo\IGetQtUsataArticoloRepo;
use Backend\Application\interfaces\repo\IVerificaPresenzaRepo;
use Backend\Application\interfaces\serv\IAggQtUsataArticolo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaUsata;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Exception;

class AggQtUsataArticolo implements IAggQtUsataArticolo{
    private IAggiornaQtUsataArticoloRepo $repoAgg;
    private IGetQtUsataArticoloRepo $repoQt;
    private IVerificaPresenzaRepo $repoVerifica;
    private ICreaPresenzaRepo $repoCrea;
    public function __construct(IAggiornaQtUsataArticoloRepo $repository, IGetQtUsataArticoloRepo $repoQt,
    IVerificaPresenzaRepo $repoVerifica, ICreaPresenzaRepo $repoCrea){
        $this->repoAgg = $repository;
        $this->repoQt = $repoQt;
        $this->repoVerifica = $repoVerifica;
        $this->repoCrea = $repoCrea;
    }
    public function execute(UpdateAggQuantitaUsataDTO $update): void {
        if ($update->quantitaAgg <= 0) {
            throw new Exception("La quantità da aggiungere deve essere positiva.");
        }

        $quantitaAttuale = $this->repoQt->getQuantita($update->articoloId, $update->numScaffale, $update->armadioId);

        if (!$quantitaAttuale) {
            $quantitaAttuale = 0;
            //throw new Exception("L'articolo con ID $update->articoloId non è presente nello scaffale $update->numScaffale dell'armadio $update->armadioId.");
        }
        if(!$this->repoVerifica->verificaPresenza($update->articoloId, $update->numScaffale, $update->armadioId))
            $this->repoCrea->creaPresenza($update->articoloId, $update->numScaffale, $update->armadioId);
        $nuovaQuantitaTotale = $quantitaAttuale + $update->quantitaAgg;

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
        $this->repoAgg->updateQuantita($quantitaAggiornataDto);
    }
}
