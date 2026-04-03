<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface ICreaTipoArticolo{
    public function execute(int $tipologiaId, array $attributi): void;
}