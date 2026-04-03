<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Domain\Entities\Tipologia;

interface ICreaTipologia{
    public function execute(Tipologia $tipologia): void;
}