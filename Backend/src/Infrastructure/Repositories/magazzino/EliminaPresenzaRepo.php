<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;

use Backend\Application\interfaces\repo\IEliminaPresenzaRepo;
use Backend\Infrastructure\DatabaseConnector;

class EliminaPresenzaRepo implements IEliminaPresenzaRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function eliminaPresenza(int $articoloId, int $numScaffale, int $armadioId): void
    {
        $db = $this->connector->get_db();
        $query = "DELETE FROM Articoli_Scaffali WHERE Articolo_Id = ? AND Numero = ? AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }
}