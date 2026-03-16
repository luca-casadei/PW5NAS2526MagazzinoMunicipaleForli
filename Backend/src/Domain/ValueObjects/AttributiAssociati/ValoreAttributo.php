<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\AttributiAssociati;

use InvalidArgumentException;

final readonly class ValoreAttributo
{
    public function __construct(public string $valore)
    {
        if (strlen(trim($valore)) < 0) {
            throw new InvalidArgumentException('La qlunghezza del valore non può essere negativa.');
        }
    }
}