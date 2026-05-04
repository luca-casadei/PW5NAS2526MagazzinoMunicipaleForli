<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche;

use Backend\Application\interfaces\repo\IEliminaArmadioRepo;
use Backend\Infrastructure\DatabaseConnector;


class EliminaArmadioRepo implements IEliminaArmadioRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function elimina(int $id): void{
        $db = $this->connector->get_db();
        $query = "DELETE FROM Armadi WHERE Armadio_Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}