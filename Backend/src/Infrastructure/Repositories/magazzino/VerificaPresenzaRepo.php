<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;

use Backend\Application\interfaces\repo\IVerificaPresenzaRepo;
use Backend\Infrastructure\DatabaseConnector;

class VerificaPresenzaRepo implements  IVerificaPresenzaRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function verificaPresenza(int $articoloId, int $numScaffale, int $armadioId): bool
    {
        $db = $this->connector->get_db();
        $query = "SELECT COUNT(*) FROM Articoli_Scaffali WHERE Articolo_Id = ? AND Numero = ? AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count > 0;
    }
}