<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface ICreaPresenzaRepo{
    public function creaPresenza(int $articoloId, int $numScaffale, int $armadioId): void;
}