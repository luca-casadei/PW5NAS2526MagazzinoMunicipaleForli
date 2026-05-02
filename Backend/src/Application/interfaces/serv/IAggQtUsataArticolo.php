<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\UpdateAggQuantitaUsataDTO;

interface IAggQtUsataArticolo{
    public function execute(UpdateAggQuantitaUsataDTO $update):void;
}