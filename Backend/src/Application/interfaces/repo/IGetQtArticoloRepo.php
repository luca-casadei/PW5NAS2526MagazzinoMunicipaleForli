<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IGetQtArticoloRepo{
    public function getQuantita(int $articoloId, int $numeroScaffale, int $armadioId):int | null;
}