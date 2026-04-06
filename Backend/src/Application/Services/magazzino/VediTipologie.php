<?php
declare(strict_types=1);
namespace Backend\Application\Services\magazzino;

use Backend\Application\interfaces\repo\IVediTutteTipologieRepo;
use Backend\Application\interfaces\serv\IVediTipologie;


class VediTipologie implements IVediTipologie{
    private IVediTutteTipologieRepo $repo;
    public function __construct(IVediTutteTipologieRepo $repository){
        $this->repo = $repository;
    }
    public function execute(): array{
        return $this->repo->getAllTipologie();
    }
}
