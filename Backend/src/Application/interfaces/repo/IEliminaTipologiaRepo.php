<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IEliminaTipologiaRepo{
    public function eliminaByNome(string $nomeTipo): void;
}