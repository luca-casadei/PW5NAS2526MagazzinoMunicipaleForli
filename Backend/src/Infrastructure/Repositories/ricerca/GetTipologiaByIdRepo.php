<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\ricerca;
use Backend\Application\interfaces\repo\IGetTipologiaByIdRepo;
use Backend\Domain\Entities\Tipologia;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\TipologiaDTO;
use Backend\Infrastructure\mapper\Mapper;


class GetTipologiaByIdRepo implements IGetTipologiaByIdRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function execute(int $id): ?Tipologia{
        $db = $this->connector->get_db();
        $query = "SELECT *
                  FROM Tipologie T
                  WHERE T.Tipologia_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if (empty($result)) {
            return null;
        }
        $dto = new TipologiaDTO($result["Tipologia_Id"], $result["Nome"], $result["Descrizione"]);
        $tipologia = Mapper::DTO_To_Tipologia($dto);
        return $tipologia;
    }
}