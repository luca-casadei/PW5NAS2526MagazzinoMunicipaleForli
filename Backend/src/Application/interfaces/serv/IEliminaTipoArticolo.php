<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IEliminaTipoArticolo{
    public function execute(int $articoloId): void;
}