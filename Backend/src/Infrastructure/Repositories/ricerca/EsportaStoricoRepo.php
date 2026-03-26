<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IEsportaStoricoRepo;
use Backend\Infrastructure\DatabaseConnector;


class EsportaStoricoRepo implements IEsportaStoricoRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}