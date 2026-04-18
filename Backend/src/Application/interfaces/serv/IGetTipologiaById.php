<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\GetTipologiaDTO;
use Backend\Domain\Entities\Tipologia;

interface IGetTipologiaById{
    public function execute(GetTipologiaDTO $id): ?Tipologia;
}