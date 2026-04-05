<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IDimQtArticolo{
    public function execute(int $articoloId, int $numeroScaffale, int $armadioId): void;
}