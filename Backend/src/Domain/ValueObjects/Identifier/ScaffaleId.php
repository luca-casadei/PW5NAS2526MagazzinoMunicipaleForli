<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Identifier;

use Backend\Domain\ValueObjects\Identifier\ArmadioId;
use Backend\Domain\ValueObjects\Value\NumeroScaffale;
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