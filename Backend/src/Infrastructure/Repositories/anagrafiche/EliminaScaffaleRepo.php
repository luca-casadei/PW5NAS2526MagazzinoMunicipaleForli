<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche;

use Backend\Application\interfaces\repo\IEliminaScaffaleRepo;
use Backend\Infrastructure\DatabaseConnector;


class EliminaScaffaleRepo implements IEliminaScaffaleRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function elimina(int $armadioId, int $numScaffale): void{
        $db = $this->connector->get_db();
        $query = "DELETE FROM Scaffali WHERE Armadio_Id = ? AND Numero = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $armadioId, $numScaffale);
        $stmt->execute();
        $stmt->close();
    }
}