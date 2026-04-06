<?php
declare(strict_types=1);
namespace Backend\Application\Services\magazzino;

use Backend\Application\interfaces\repo\ICreaArmadioRepo;
use Backend\Application\interfaces\serv\ICreaArmadio;


class CreaArmadio implements ICreaArmadio{
    private ICreaArmadioRepo $repo;
    public function __construct(ICreaArmadioRepo $repository){
        $this->repo = $repository;
    }
    public function execute(): void{
        $this->repo->execute();
    }
}
