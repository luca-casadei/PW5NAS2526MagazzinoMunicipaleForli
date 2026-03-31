<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IAggQtArticoloRepo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Infrastructure\DatabaseConnector;


class AggQtArticoloRepo implements IAggQtArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function updateQuantita(ArticoliInScaffali $articoliInScaffali): void
    {
        $db = $this->connector->get_db();
        $query = "UPDATE Articoli_Scaffali
                  SET Quantita = ? 
                  WHERE Articolo_Id = ? 
                    AND Numero = ? 
                    AND Armadio_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iiii", 
            $articoliInScaffali->quantita,
            $articoliInScaffali->id->idArticolo,
            $articoliInScaffali->id->idScaffale->numScaffale,
            $articoliInScaffali->id->idScaffale->idArmadio
        );
        $stmt->execute();
    }
}