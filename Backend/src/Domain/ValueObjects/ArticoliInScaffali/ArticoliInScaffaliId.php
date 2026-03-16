<?php

declare(strict_types=1);

namespace Backend\Domain\ValueObjects\ArticoliInScaffali;

use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;

final readonly class ArticoliInScaffaliId
{
    public function __construct(public ArticoloId $idArticolo, public ScaffaleId $idScaffale)
    {
    }
}