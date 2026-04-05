<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\CreateTipoArticoloDTO;

interface ICreaTipoArticolo{
    public function execute(CreateTipoArticoloDTO $command): void;
}