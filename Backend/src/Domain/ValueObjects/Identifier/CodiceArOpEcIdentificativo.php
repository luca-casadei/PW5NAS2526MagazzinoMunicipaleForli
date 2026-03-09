<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Identifier;

use InvalidArgumentException;

final readonly class CodiceArOpEcIdentificativo
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