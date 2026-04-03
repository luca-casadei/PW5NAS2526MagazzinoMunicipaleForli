<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\ICreaTipologiaRepo;
use Backend\Application\interfaces\serv\ICreaTipologia;
use Backend\Domain\Entities\Tipologia;


class CreaTipologia implements ICreaTipologia{
    private ICreaTipologiaRepo $repo;
    public function __construct(ICreaTipologiaRepo $repository){
        $this->repo = $repository;
    }
    public function execute(Tipologia $tipologia): void{
        $this->repo->execute($tipologia);
    }
}
