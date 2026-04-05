<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;
interface IVediArticoliByNomeRepo
{
    public function getArticoliByNome(string $nome): array;
}