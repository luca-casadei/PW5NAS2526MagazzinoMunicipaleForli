<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\serv\IDimQtArticolo;

class DimQtArticolo implements IDimQtArticolo{
    private ISchoolClassRepository $repo;
    public function __construct(ISchoolClassRepository $repository){
        $this->repo = $repository;
    }
}
