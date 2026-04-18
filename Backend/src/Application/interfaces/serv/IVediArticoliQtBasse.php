<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\dtos\ReadQuantitaDTO;

interface IVediArticoliQtBasse{
    public function execute(ReadQuantitaDTO $quantitaMinima): array;
}