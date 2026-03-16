<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Tipologia;

use InvalidArgumentException;

final readonly class TipologiaNome
{
    public function __construct(public string $nome)
    {
        if (strlen(trim($nome)) < 0) {
            throw new InvalidArgumentException('la lunghezza del nome deve essere positiva.');
        }
    }
}