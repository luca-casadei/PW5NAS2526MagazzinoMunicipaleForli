<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\commands\CreateScaffaleDTO;
use Backend\Application\interfaces\repo\ICreaScaffaleRepo;
use Backend\Application\interfaces\serv\ICreaScaffale;
use Backend\Application\mappers\CreateMapper;


class CreaScaffale implements ICreaScaffale{
    private ICreaScaffaleRepo $repo;
    public function __construct(ICreaScaffaleRepo $repository){
        $this->repo = $repository;
    }
    public function execute(CreateScaffaleDTO $dto): void{
        $scaffale = CreateMapper::DTO_To_Scaffale($dto);
        return $this->repo->execute($scaffale);
    }
}
