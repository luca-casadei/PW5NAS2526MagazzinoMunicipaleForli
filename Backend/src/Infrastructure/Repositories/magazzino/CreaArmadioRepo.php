<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Application\interfaces\repo\ICreaArmadioRepo;
use Backend\Infrastructure\DatabaseConnector;


class CreaArmadioRepo implements ICreaArmadioRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function execute(): void
    {
        $db = $this->connector->get_db();
        $query = "INSERT INTO Armadi () VALUES ();";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->close();
    }
}