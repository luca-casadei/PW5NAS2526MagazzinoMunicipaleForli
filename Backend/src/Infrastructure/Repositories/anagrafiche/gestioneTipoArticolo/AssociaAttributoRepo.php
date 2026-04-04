<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\commands\CreateAttrArtDTO;
use Backend\Application\interfaces\repo\IAssociaAttributoRepo;
use Backend\Infrastructure\DatabaseConnector;


class AssociaAttributoRepo implements IAssociaAttributoRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function associaAttributo(CreateAttrArtDTO $dto): void {
        $db = $this->connector->get_db();
        
        $query = "INSERT INTO Attributi_Associati (Articolo_Id, Attributo_Id, Valore) VALUES (?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iis", $dto->articoloId, $dto->attributoId, $dto->valore);
        $stmt->execute();
        $stmt->close();
    }
}

