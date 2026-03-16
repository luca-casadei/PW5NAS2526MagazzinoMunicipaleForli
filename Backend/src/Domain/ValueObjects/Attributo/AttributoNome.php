<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Attributo;

use InvalidArgumentException;

final readonly class AttributoNome
{
    public function __construct(public string $nome)
    {
        if (strlen(trim($nome)) < 0) {
            throw new InvalidArgumentException('la lunghezza del nome deve essere positiva.');
        }
    }
}

//METTO ANCHE VALORE COME PARAMETRO????