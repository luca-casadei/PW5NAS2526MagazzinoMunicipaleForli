<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface ILocalizzaArticoloRepo{
    public function localizzaById(int $id): array;
}