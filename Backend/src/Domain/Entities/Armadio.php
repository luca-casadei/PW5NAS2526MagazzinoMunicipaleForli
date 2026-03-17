<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;

final readonly class Armadio
{
    public function __construct(public ArmadioId $id)
    {
    }
}

