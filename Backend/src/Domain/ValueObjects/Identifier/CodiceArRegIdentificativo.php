<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Identifier;

use InvalidArgumentException;

final readonly class CodiceArRegIdentificativo
{
    public function __construct(public string $valore)
    {
        if ($valore === '') {
            throw new InvalidArgumentException('Il codice regionale non può essere vuoto.');
        }

        if (mb_strlen($valore) > 50) {
            throw new InvalidArgumentException('Il codice regionale è troppo lungo (massimo 50 caratteri).');
        }
    }
}