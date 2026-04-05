<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IGetTipologiaByIdRepo;
use Backend\Application\interfaces\serv\IGetTipologiaById;
use Backend\Domain\Entities\Tipologia;


class GetTipologiaById implements IGetTipologiaById{
    private IGetTipologiaByIdRepo $repo;
    public function __construct(IGetTipologiaByIdRepo $repository){
        $this->repo = $repository;
    }
    public function execute(int $id): ?Tipologia{
        return $this->repo->execute($id);
    }
}
