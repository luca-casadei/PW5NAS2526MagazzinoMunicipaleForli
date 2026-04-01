<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;
interface IVediArticoliRepo
{
    // Recupera la lista base di tutti gli articoli.
    // Restituisce un array di Model parziali o ID.
    public function getAllArticoli(): array;
}