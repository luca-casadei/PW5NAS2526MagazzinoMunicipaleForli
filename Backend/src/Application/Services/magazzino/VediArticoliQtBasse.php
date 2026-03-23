<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\serv\IVediArticoliQtBasse;

class VediArticoliQtBasse implements IVediArticoliQtBasse{
    private ISchoolClassRepository $repo;
    public function __construct(ISchoolClassRepository $repository){
        $this->repo = $repository;
    }
}
