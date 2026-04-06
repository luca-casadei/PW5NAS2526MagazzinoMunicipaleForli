<?php

declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Application\interfaces\repo\IVediTutteTipologieRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\TipologiaDTO;
use Backend\Infrastructure\mapper\Mapper;

// Implementazione reale del repository che rispetta l'interfaccia granulare.
// Mantiene l'esatta struttura fornita nell'esempio.
class VediTutteTipologieRepo implements IVediTutteTipologieRepo
{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector)
    {
        $this->connector = $connector;
    }
    public function getAllTipologie(): array
    {
        $db = $this->connector->get_db();

        // Modifica logica fondamentale: selezioniamo TUTTE le tipologie, senza filtri.
        $query = "SELECT Tipologia_Id, Nome, Descrizione
                  FROM Tipologie;";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $tipologie = [];

        foreach ($result as $res) {
            $dto = new TipologiaDTO($res["Tipologia_Id"], $res["Nome"], $res["Descrizione"]);
            $tipologie[] = Mapper::DTO_To_Tipologia($dto);
        }
        return $tipologie;
    }
}