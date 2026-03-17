<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;

final readonly class Scaffale
{
    public function __construct(
        public ScaffaleId $id
    )
    {
    }
}
