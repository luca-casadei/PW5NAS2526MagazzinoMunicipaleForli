<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediScaffaliRepo;
use Backend\Infrastructure\DatabaseConnector;


class VediScaffaliRepo implements IVediScaffaliRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}