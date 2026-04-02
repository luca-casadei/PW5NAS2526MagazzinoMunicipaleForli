<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IVediArticoliQtBasse{
    public function execute(int $quantitaMinima): array;
}