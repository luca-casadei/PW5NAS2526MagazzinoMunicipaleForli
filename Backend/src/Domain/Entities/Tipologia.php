<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\Tipologia\TipologiaDescrizione;
use Backend\Domain\ValueObjects\Tipologia\TipologiaId;
use Backend\Domain\ValueObjects\Tipologia\TipologiaNome;

final readonly class Tipologia
{
    public function __construct(
        public TipologiaId $id,
        public TipologiaNome $nome,
        public TipologiaDescrizione $descrizione
    )
    {
    }
}
