<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IGetAttributiByArtIdRepo{
    public function getAttributiByArticoloId(int $articoloId): array;
}