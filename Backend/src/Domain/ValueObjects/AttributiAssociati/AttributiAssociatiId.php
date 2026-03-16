<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\AttributiAssociati;

use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Attributo\AttributoId;

final readonly class AttributiAssociatiId
{
    public function __construct(public ArticoloId $idArticolo, public AttributoId $idAttributo)
    {
    }
}