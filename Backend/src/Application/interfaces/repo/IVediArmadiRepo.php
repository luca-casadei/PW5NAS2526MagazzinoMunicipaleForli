<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IVediArmadiRepo{
    public function getScaffaliArmadioById(int $id): ?array;
    public function getAllArmadi(): array;
}