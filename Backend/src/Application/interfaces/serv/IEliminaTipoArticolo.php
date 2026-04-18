<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\DeleteTipoArticoloDTO;

interface IEliminaTipoArticolo{
    public function execute(DeleteTipoArticoloDTO $delTipo): void;
}