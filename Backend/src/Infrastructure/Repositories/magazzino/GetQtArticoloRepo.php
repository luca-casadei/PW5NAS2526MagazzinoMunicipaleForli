<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Application\interfaces\repo\IGetQtArticoloRepo;
class GetQtArticoloRepo implements IGetQtArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getQuantita(int $articoloId, int $numeroScaffale, int $armadioId): int | null
    {
        $db = $this->connector->get_db();
        $query = "SELECT Quantita 
                  FROM Articoli_Scaffali
                  WHERE Articolo_Id = ? 
                    AND Numero = ? 
                    AND Armadio_Id = ?;";

        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numeroScaffale, $armadioId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();

        return $row['Quantita'];
    }
}