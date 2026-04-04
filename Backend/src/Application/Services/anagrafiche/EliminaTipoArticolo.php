<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IEliminaArticoloRepo;
use Backend\Application\interfaces\serv\IEliminaTipoArticolo;


class EliminaTipoArticolo implements IEliminaTipoArticolo{
    private IEliminaArticoloRepo $repo;
    public function __construct(IEliminaArticoloRepo $repository){
        $this->repo = $repository;
    }
    public function execute(int $articoloId): void{
        $this->repo->eliminaById($articoloId);
    }
}
