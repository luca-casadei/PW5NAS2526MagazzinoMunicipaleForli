<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\ArtOpEconomico\ArtOpEcoDescrizione;
use Backend\Domain\ValueObjects\ArtOpEconomico\ArtOpEcoIdentificativo;


final readonly class ArtOpEconomico
{
    public function __construct(
        public ArtOpEcoIdentificativo $id,
        public ArtOpEcoDescrizione $descrizione
    )
    {
    }
}
