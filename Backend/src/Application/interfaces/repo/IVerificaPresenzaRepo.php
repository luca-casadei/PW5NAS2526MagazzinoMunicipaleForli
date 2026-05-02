<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IVerificaPresenzaRepo{
    public function verificaPresenza(int $articoloId, int $numScaffale, int $armadioId): bool;
}