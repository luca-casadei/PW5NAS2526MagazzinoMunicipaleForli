<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;


final readonly class ArticoliInScaffali
{
    public function __construct(public ArticoliInScaffaliId $id, public QuantitaScorta $quantita)
    {
    }
}
