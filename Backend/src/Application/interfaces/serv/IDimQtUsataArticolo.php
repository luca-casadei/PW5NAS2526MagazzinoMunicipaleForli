<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\UpdateDimQuantitaUsataDTO;

interface IDimQtUsataArticolo{
    public function execute(UpdateDimQuantitaUsataDTO $update): void;
}