<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo\gestioneTipoArticolo;

interface ICreaAttributoRepo {
public function creaAttributo(string $nome, string $descrizione): int;
}