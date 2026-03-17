<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\ArtRegionale\ArtRegIdentificativo;
use Backend\Domain\ValueObjects\ArtRegionale\ArtRegionaleDescrizione;


final readonly class ArtRegionale
{
    public function __construct(
        public ArtRegIdentificativo $id,
        public ArtRegionaleDescrizione $descrizione
    )
    {
    }
}
