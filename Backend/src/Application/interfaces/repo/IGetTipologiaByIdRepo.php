<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\Tipologia;

interface IGetTipologiaByIdRepo{
    public function execute(int $id): ?Tipologia;
}