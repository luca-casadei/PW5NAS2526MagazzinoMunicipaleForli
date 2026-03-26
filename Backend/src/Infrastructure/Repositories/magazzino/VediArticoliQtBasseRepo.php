<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediArticoliQtBasseRepo;
use Backend\Infrastructure\DatabaseConnector;


class VediArticoliQtBasseRepo implements IVediArticoliQtBasseRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}