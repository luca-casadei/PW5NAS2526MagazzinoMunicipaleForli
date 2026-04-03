<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IGetAttributiArticoloConIdRepo{
    public function getArtById(int $id): array;
}