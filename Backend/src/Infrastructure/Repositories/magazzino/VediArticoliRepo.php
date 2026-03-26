<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediArticoliRepo;
use Backend\Infrastructure\DatabaseConnector;


class VediArticoliRepo implements IVediArticoliRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}