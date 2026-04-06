<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo\gestioneTipoArticolo;

interface ICreaArticoloRepo {
public function creaArticolo(string $nome, int $tipologiaId): int;
}