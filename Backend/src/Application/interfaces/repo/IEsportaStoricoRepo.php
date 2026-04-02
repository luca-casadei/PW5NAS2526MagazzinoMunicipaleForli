<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IEsportaStoricoRepo{
    public function getMovimentiByDateRange(string $dataInizio, string $dataFine): array;
}