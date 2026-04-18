<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\commands\GetArticoloDTO;
use Backend\Application\interfaces\repo\ILocalizzaArticoloRepo;
use Backend\Application\interfaces\serv\ILocalizzaArticolo;


class LocalizzaArticolo implements ILocalizzaArticolo{
    private ILocalizzaArticoloRepo $repo;
    public function __construct(ILocalizzaArticoloRepo $repository){
        $this->repo = $repository;
    }
    public function localizzaById(GetArticoloDTO $id): array{
        return $this->repo->localizzaById($id->articoloId);
    }
}