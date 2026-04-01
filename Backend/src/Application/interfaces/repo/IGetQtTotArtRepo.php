<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IGetQtTotArticoloRepo{
    public function getQuantita(int $articoloId):int | null;
}