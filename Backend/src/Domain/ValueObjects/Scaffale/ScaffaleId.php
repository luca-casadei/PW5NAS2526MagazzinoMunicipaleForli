<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Scaffale;

use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
/**
 * Wrapper immutabile per l'identificativo unico dell'articolo.
 */
final readonly class ScaffaleId
{
    /**
     * 
     */
    public function __construct(public ArmadioId $idArmadio, public NumeroScaffale $numScaffale)
    {
    }
}