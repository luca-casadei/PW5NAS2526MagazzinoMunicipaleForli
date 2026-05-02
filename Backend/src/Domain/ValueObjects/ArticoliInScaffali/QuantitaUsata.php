<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\ArticoliInScaffali;

use InvalidArgumentException;

final readonly class QuantitaUsata
{
    public function __construct(public int $valore)
    {
        if ($valore < 0) {
            throw new InvalidArgumentException('La quantità usata in scorta non può essere negativa.');
        }
    }
}