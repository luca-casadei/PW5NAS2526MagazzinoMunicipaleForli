<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Tipologia\TipologiaId;

final readonly class Articolo
{
    public function __construct(
        public ArticoloId $id,
        public TipologiaId $tipologiaId
    )
    {
    }
}
