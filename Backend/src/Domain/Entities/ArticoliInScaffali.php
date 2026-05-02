<?php

declare(strict_types=1);

namespace Backend\Domain\Entities;

use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaUsata;


final readonly class ArticoliInScaffali
{
    public function __construct(public ArticoliInScaffaliId $id, public QuantitaScorta $quantita, public QuantitaUsata $qtUsata)
    {
    }
}
