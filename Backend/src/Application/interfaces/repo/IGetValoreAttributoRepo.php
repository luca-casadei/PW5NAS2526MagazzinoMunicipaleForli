<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IGetValoreAttributoRepo{
    public function getValore(int $articoloId, int $attributoId): string;
}