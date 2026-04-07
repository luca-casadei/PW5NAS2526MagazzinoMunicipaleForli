<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\Scaffale;
interface IVediArticoliPerScaffaleRepo
{
    // Recupera la lista base di tutti gli articoli.
    // Restituisce un array di Model parziali o ID.
    public function getArticoli(Scaffale $scaffale): array;
}