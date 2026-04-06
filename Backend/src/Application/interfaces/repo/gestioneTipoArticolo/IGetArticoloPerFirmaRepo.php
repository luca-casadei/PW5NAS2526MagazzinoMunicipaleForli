<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo\gestioneTipoArticolo;

interface IGetArticoloPerFirmaRepo {
public function trovaArticolo(string $nome, int $tipologiaId, array $attributiIds): ?int;
}