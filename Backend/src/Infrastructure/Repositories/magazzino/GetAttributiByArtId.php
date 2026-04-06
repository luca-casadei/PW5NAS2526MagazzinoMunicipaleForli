<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Application\interfaces\repo\IGetAttributiByArtIdRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\AttributoDTO;
use Backend\Infrastructure\mapper\Mapper;


class GetAttributiByArtId implements IGetAttributiByArtIdRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getAttributiByArticoloId(int $articoloId): array{
        $db = $this->connector->get_db();
        $query = "SELECT A.Attributo_Id, A.Nome
                  FROM Attributi_Articoli A
                  JOIN Attributi_Associati AA ON A.Attributo_Id = AA.Attributo_Id
                  WHERE AA.Articolo_Id = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $articoloId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $attributi = [];
        foreach($result as $res){
            $dto = new AttributoDTO($res["Attributo_Id"], $res["Nome"]);
            $attributi[] = Mapper::DTO_To_Attributo($dto);
        }
        return $attributi;
    }
}