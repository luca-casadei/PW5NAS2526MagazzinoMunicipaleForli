<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IAssociaAttributoRepo;
use Backend\Infrastructure\DatabaseConnector;


class AssociaAttributoRepo implements IAssociaAttributoRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function associaAttributo(int $articoloId, int $attributoId, string $valore): void {
        $db = $this->connector->get_db();
        
        $query = "INSERT INTO Attributi_Associati (Articolo_Id, Attributo_Id, Valore) VALUES (?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iis", $articoloId, $attributoId, $valore);
        $stmt->execute();
        $stmt->close();
    }
}

