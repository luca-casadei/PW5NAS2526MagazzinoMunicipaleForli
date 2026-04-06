<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Tipologia;

use InvalidArgumentException;

final readonly class TipologiaDescrizione
{
    public function __construct(public string $descrizione)
    {
        if (mb_strlen($descrizione) > 255) {
            throw new InvalidArgumentException('La lunghezza della descrizione deve essere minore di 255.');
        }
    }
}