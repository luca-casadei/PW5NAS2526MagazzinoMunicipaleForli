<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Application\interfaces\repo\IVediTipologieRepo;
use Backend\Domain\Entities\Tipologia;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\TipologiaDTO;
use Backend\Infrastructure\mapper\Mapper;


class VediTipologieRepo implements IVediTipologieRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getTipologiaByArticoloId(int $articoloId): Tipologia{
        $db = $this->connector->get_db();
        $query = $query = "SELECT T.Tipologia_Id, T.Nome, T.Descrizione
                  FROM Tipologie T
                  JOIN Articoli A ON T.Tipologia_Id = A.Tipologia_Id
                  WHERE A.Articolo_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $articoloId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $dto = new TipologiaDTO($res["Tipologia_Id"], $res["Nome"], $res["Descrizione"]);
        $tipologia = Mapper::DTO_To_Tipologia($dto);
        return $tipologia;
    }
}