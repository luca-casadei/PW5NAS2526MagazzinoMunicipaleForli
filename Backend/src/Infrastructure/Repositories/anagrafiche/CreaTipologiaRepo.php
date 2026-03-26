<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\ICreaTipologiaRepo;
use Backend\Infrastructure\DatabaseConnector;


class CreaTipologiaRepo implements ICreaTipologiaRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}