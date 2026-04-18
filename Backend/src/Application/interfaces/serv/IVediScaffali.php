<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\dtos\ReadScaffaleDTO;

interface IVediScaffali{
    public function getContenutoById(ReadScaffaleDTO $readScaffale): array;
}