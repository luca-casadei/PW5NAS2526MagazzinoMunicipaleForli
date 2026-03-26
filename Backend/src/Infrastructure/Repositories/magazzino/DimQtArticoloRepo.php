<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IDimQtArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;


class DimQtArticoloRepo implements IDimQtArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
}