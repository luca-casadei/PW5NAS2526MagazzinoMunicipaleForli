<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\Tipologia;

interface IVediTipologieRepo{
    public function getTipologiaByArticoloId(int $articoloId): Tipologia;
}