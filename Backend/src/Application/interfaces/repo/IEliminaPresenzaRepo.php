<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IEliminaPresenzaRepo{
    public function eliminaPresenza(int $articoloId, int $numScaffale, int $armadioId): void;
}