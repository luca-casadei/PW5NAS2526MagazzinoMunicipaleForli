<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo\gestioneTipoArticolo;

interface IGetAttributoByIdRepo {
public function findIdByNome(string $nome): ?int;
}