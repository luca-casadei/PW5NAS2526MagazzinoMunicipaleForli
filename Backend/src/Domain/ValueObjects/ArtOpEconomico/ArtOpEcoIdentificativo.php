<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\ArtOpEconomico;

use InvalidArgumentException;

final readonly class ArtOpEcoIdentificativo
{
    public function __construct(public string $valore)
    {
        if ($valore === '') {
            throw new InvalidArgumentException('Il codice interno fornitore non può essere vuoto.');
        }

        if (mb_strlen($valore) > 50) {
            throw new InvalidArgumentException('Il codice interno fornitore è troppo lungo (massimo 50 caratteri).');
        }
    }
}