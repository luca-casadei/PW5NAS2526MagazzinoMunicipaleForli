<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IAggQtArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;


class AggQtArticoloRepo implements IAggQtArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}