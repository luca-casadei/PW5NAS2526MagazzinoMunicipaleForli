<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\ArtRegionale;

use InvalidArgumentException;

final readonly class ArtRegionaleDescrizione
{
    public function __construct(public string $descrizione)
    {
        if (strlen(trim($descrizione)) < 0) {
            throw new InvalidArgumentException('La lunghezza della descrizione deve essere positiva.');
        }
    }
}