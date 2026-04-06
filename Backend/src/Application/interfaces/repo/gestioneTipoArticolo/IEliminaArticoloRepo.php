<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo\gestioneTipoArticolo;

interface IEliminaArticoloRepo {
public function eliminaById(int $articoloId): void;
}