<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IAggQtArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;


class AggQtArticoloRepo implements IAggQtArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function aggiungiQuantita(int $idArticolo, int $numScaffale, int $idArmadio): void{
        $db = $this->connector->get_db();
        $query = "UPDATE Articoli_Scaffali
            SET Quantita = Quantita + 1
            WHERE Articolo_Id = ?
                AND Numero = ? 
                AND Armadio_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $idArticolo, $numScaffale, $idArmadio);
        $stmt->execute();
    }
}