<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\serv\IEliminaTipologia;


class EliminaTipologia implements IEliminaTipologia{
    private ISchoolClassRepository $repo;
    public function __construct(ISchoolClassRepository $repository){
        $this->repo = $repository;
    }
}
