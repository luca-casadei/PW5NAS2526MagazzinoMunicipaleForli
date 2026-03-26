<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IEliminaTipologiaRepo;
use Backend\Infrastructure\DatabaseConnector;


class EliminaTipologiaRepo implements IEliminaTipologiaRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}