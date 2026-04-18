<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\GetArticoloDTO;

interface ILocalizzaArticolo{
    public function localizzaById(GetArticoloDTO $id): array;
}