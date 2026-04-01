<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IGetValoreAttributoRepo;
use Backend\Infrastructure\DatabaseConnector;


class GetValoriAttributi implements IGetValoreAttributoRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getValore(int $articoloId, int $attributoId): string{
        $db = $this->connector->get_db();
        $query = "SELECT AA.Valore
                  FROM Attributi_Articoli A
                  JOIN Attributi_Associati AA ON A.Attributo_Id = AA.Attributo_Id
                  WHERE AA.Articolo_Id = ? AND A.Attributo_Id = ?;";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $articoloId, $attributoId);
        $stmt->execute();

        // Recuperiamo il risultato
        $result = $stmt->get_result()->fetch_assoc();
        return $result['Valore'];
    }
}