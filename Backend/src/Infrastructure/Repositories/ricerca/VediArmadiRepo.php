<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Infrastructure\DatabaseConnector;


class VediArmadiRepo implements IVediArmadiRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}