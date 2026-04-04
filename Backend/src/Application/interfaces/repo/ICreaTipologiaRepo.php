<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\Tipologia;

interface ICreaTipologiaRepo{
    public function execute(Tipologia $tipologia): void;
    public function verificaEsistenza(string $nome): bool;
}