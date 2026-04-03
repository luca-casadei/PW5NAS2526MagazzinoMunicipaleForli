<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IEliminaTipologiaRepo;
use Backend\Application\interfaces\serv\IEliminaTipologia;


class EliminaTipologia implements IEliminaTipologia{
    private IEliminaTipologiaRepo $repo;
    public function __construct(IEliminaTipologiaRepo $repository){
        $this->repo = $repository;
    }
    public function eliminaByNome(string $nomeTipo): void{
        $this->repo->eliminaByNome($nomeTipo);
    }
}
