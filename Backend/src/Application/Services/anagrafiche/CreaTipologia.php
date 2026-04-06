<?php
declare(strict_types=1);
namespace Backend\Application\Services\anagrafiche;

use Backend\Application\commands\CreateTipologiaDTO;
use Backend\Application\interfaces\repo\ICreaTipologiaRepo;
use Backend\Application\interfaces\serv\ICreaTipologia;
use Backend\Application\mappers\CreateMapper;


class CreaTipologia implements ICreaTipologia{
    private ICreaTipologiaRepo $repo;
    public function __construct(ICreaTipologiaRepo $repository){
        $this->repo = $repository;
    }
    public function execute(CreateTipologiaDTO $tipologia): void{
        if ($this->repo->verificaEsistenza($tipologia->nome)) {
            throw new \Exception("Errore: Esiste già una tipologia con questo nome.");
        }
        $tipo = CreateMapper::DTO_To_Tipologia($tipologia);
        $this->repo->execute($tipo);
    }
}
