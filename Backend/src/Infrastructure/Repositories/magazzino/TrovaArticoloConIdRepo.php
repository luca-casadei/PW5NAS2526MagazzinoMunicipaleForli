<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\ITrovaArticoloConIdRepo;
use Backend\Infrastructure\DatabaseConnector;


class TrovaArticoloConIdRepo implements ITrovaArticoloConIdRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getArtById(int $id)
    {
        $db = $this->connector->get_db();
        $query = "SELECT C.nome, C.link, C.materia, U.email 
        FROM Classi AS C JOIN Utenti AS U ON C.Id_Utente_Respo = U.Utente_Id 
        WHERE C.Classe_Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if (!$res) {
            return null;
        }
        $dto = new SchoolClassDTO($id,$res["nome"], $res["link"], $res["materia"], $res["email"]);
        return Mapper::DTO_To_SchoolClass($dto);
    }
}