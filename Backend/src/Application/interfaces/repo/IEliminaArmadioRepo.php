<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IEliminaArmadioRepo{
    public function elimina(int $id): void;
}