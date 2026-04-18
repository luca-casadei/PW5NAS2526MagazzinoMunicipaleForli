<?php
declare(strict_types=1);
namespace Backend\Application\Services\anagrafiche;

use Backend\Application\commands\DeleteTipoArticoloDTO;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\IEliminaArticoloRepo;
use Backend\Application\interfaces\serv\IEliminaTipoArticolo;


class EliminaTipoArticolo implements IEliminaTipoArticolo{
    private IEliminaArticoloRepo $repo;
    public function __construct(IEliminaArticoloRepo $repository){
        $this->repo = $repository;
    }
    public function execute(DeleteTipoArticoloDTO $delTipo): void{
        $this->repo->eliminaById($delTipo->articoloId);
    }
}
