<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\UpdateAggQuantitaDTO;

interface IAggQtArticolo{
    public function execute(UpdateAggQuantitaDTO $update):void;
}