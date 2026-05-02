<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;

use Backend\Application\interfaces\repo\IAggiornaQtUsataArticoloRepo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\mapper\Mapper;

class AggQtUsataArticoloRepo implements IAggiornaQtUsataArticoloRepo {
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function updateQuantita(ArticoliInScaffali $articoliInScaffali): void
    {
        $dto = Mapper::ArticoliInScaffali_To_DTO($articoliInScaffali);
        
        $quantita = $dto->qtUsata;
        $articoloId = $dto->articoloId;
        $numScaffale = $dto->numeroScaffale;
        $armadioId = $dto->armadioId;

        $db = $this->connector->get_db();
        $query = "UPDATE Articoli_Scaffali
                  SET Qt_Usata = ? 
                  WHERE Articolo_Id = ? 
                    AND Numero = ? 
                    AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iiii", $quantita, $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }
}