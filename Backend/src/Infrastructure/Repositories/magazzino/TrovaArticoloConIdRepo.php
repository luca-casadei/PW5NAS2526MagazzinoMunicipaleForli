<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\ITrovaArticoloConIdRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\AttributiAssociatiDTO;
use Backend\Infrastructure\mapper\Mapper;


class TrovaArticoloConIdRepo implements ITrovaArticoloConIdRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getArtById(int $id): array
    {
        $db = $this->connector->get_db();
        $query = "SELECT AA.Articolo_Id, AA.Attributo_Id, AA.Valore 
        FROM Attributi_Associati AS AA
        WHERE AA.Articolo_Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $attributi = [];
        foreach($result as $res){
            $dto = new AttributiAssociatiDTO($res["Articolo_Id"], $res["Attributo_Id"], $res["Valore"]);
            $attributi[] = Mapper::DTO_To_AttributiAssociati($dto);
        }
        return $attributi;
    }
}