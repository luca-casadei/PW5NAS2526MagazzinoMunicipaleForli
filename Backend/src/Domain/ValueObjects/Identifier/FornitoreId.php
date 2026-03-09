<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Identifier;

use InvalidArgumentException;

final readonly class FornitoreId
{
    public function __construct(public string $ragioneSociale)
    {
        if ($ragioneSociale === '') {
            throw new InvalidArgumentException('La ragione sociale del fornitore non può essere vuota.');
        }

        if (mb_strlen($ragioneSociale) > 150) {
            throw new InvalidArgumentException('La ragione sociale del fornitore è troppo lunga (massimo 150 caratteri).');
        }
    }
}