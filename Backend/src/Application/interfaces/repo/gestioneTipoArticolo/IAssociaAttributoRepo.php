<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IAssociaAttributoRepo {
    public function associaAttributo(int $articoloId, int $attributoId, string $valore): void;
}