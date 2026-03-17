<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\Fornitore\FornitoreId;

final readonly class Fornitore
{
    public function __construct(
        public FornitoreId $id
    )
    {
    }
}
