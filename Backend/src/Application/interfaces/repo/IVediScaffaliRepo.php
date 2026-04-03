<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IVediScaffaliRepo{
        public function getScaffaliArmadioById(int $id): ?array;
}