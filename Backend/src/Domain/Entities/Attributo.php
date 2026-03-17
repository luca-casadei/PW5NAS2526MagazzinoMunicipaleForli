<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\Attributo\AttributoId;
use Backend\Domain\ValueObjects\Attributo\AttributoNome;

final readonly class Attributo
{
    public function __construct(
        public AttributoId $id,
        public AttributoNome $nome
    )
    {
    }
}
