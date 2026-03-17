<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\AttributiAssociati\AttributiAssociatiId;
use Backend\Domain\ValueObjects\AttributiAssociati\ValoreAttributo;

final readonly class AttributiAssociati
{
    public function __construct(
        public AttributiAssociatiId $id,
        public ValoreAttributo $valore
    )
    {
    }
}
