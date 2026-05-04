<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IEliminaScaffaleRepo{
    public function elimina(int $armadioId, int $numScaffale): void;
}