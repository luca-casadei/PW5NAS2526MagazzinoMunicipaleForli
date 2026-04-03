<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IVediScaffali{
    public function getContenutoById(int $armadioId, int $numScaffale): array;
}