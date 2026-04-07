<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Application\interfaces\repo\IGetQtTotArticoloRepo;
class GetQtTotArtRepo implements IGetQtTotArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getQuantita(int $articoloId): int | null
    {
        $db = $this->connector->get_db();
        $query = "SELECT SUM(Quantita) AS QuantitaTotale
                  FROM Articoli_Scaffali
                  WHERE Articolo_Id = ?;";

        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $articoloId);
        $stmt->execute();

        // Recuperiamo il risultato
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }
        $row = $result->fetch_assoc();

        return (int)$row['QuantitaTotale'];
    }
}