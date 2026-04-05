<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Domain\Entities\Tipologia;

interface IGetTipologiaById{
    public function execute(int $id): ?Tipologia;
}