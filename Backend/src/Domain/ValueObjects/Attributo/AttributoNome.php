<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\Attributo;

use InvalidArgumentException;

final readonly class AttributoNome
{
    public function __construct(public string $nome)
    {
        if (strlen(trim($nome)) <= 0) {
            throw new InvalidArgumentException('la lunghezza del nome deve essere positiva.');
        }
        if (mb_strlen($nome) > 100) {
            throw new InvalidArgumentException('La lunghezza del nome deve essere minore di 100.');
        }
    }
}
