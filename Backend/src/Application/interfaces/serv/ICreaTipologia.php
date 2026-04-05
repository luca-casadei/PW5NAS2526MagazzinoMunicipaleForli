<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\CreateTipologiaDTO;
interface ICreaTipologia{
    public function execute(CreateTipologiaDTO $tipologia): void;
}