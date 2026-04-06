<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Application\interfaces\serv\IVediArmadi;


class VediArmadi implements IVediArmadi{
    private IVediArmadiRepo $repo;
    public function __construct(IVediArmadiRepo $repository){
        $this->repo = $repository;
    }
    public function execute(): array{
        return $this->repo->getAllArmadi();
    }
}