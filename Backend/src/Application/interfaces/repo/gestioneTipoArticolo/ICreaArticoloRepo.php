<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface ICreaArticoloRepo {
public function creaArticolo(string $nome, int $tipologiaId): int;
}