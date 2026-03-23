<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\serv\IVediScaffali;


class VediScaffali implements IVediScaffali{
    private ISchoolClassRepository $repo;
    public function __construct(ISchoolClassRepository $repository){
        $this->repo = $repository;
    }
}