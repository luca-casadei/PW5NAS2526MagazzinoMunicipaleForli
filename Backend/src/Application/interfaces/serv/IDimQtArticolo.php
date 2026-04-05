<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\UpdateDimQuantitaDTO;

interface IDimQtArticolo{
    public function execute(UpdateDimQuantitaDTO $update): void;
}