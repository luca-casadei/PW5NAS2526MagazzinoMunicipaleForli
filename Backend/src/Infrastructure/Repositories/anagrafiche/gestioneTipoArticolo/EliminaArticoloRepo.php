<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IEliminaArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;


class EliminaArticoloRepo implements IEliminaArticoloRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function eliminaById(int $articoloId): void {
        $db = $this->connector->get_db();
        
        $query = "DELETE FROM Articoli WHERE Articolo_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $articoloId);
        $stmt->execute();
        
        $stmt->close();
    }
}

