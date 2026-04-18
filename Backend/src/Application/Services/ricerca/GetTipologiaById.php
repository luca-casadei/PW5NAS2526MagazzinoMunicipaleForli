<?php
declare(strict_types=1);
namespace Backend\Application\Services\ricerca;

use Backend\Application\commands\GetTipologiaDTO;
use Backend\Application\interfaces\repo\IGetTipologiaByIdRepo;
use Backend\Application\interfaces\serv\IGetTipologiaById;
use Backend\Domain\Entities\Tipologia;


class GetTipologiaById implements IGetTipologiaById{
    private IGetTipologiaByIdRepo $repo;
    public function __construct(IGetTipologiaByIdRepo $repository){
        $this->repo = $repository;
    }
    public function execute(GetTipologiaDTO $id): ?Tipologia{
        return $this->repo->execute($id->tipoId);
    }
}
