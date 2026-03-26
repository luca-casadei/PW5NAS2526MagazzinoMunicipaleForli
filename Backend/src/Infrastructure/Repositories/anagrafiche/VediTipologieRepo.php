<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Infrastructure\DatabaseConnector;


class VediTipologieRepo implements IVediTipologieRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}