<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;

use Backend\Application\interfaces\repo\ICreaPresenzaRepo;
use Backend\Infrastructure\DatabaseConnector;

class CreaPresenzaRepo implements ICreaPresenzaRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function creaPresenza(int $articoloId, int $numScaffale, int $armadioId): void
    {
        $db = $this->connector->get_db();
        $query = "INSERT INTO Articoli_Scaffali (Articolo_Id, Numero, Armadio_Id, Quantita, Qt_Usata) VALUES (?, ?, ?, 0, 0);";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }
}