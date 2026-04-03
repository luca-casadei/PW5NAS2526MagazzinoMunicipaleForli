<?php
declare(strict_types=1);

namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\ArmadioDTO;
use Backend\Infrastructure\dtos\ScaffaleDTO;
use Backend\Infrastructure\mapper\Mapper;

class VediArmadiRepo implements IVediArmadiRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getScaffaliArmadioById(int $id): ?array{
        $db = $this->connector->get_db();
        
        $query = "SELECT Numero FROM Scaffali WHERE Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        if ($result === null || count($result) === 0) {
            return null;
        }
        
        $numeriScaffali = [];
        foreach ($result as $elem) {
            $dto = new ScaffaleDTO($id, $elem['Numero']);
            array_push($numeriScaffali, Mapper::DTO_To_Scaffale($dto));
        }
        return $numeriScaffali;
    }
    public function getAllArmadi(): array{
        $db = $this->connector->get_db();
        
        $query = "SELECT Armadio_Id FROM Armadi;";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $idArmadi = []; 
        foreach ($result as $elem) {
            $dto = new ArmadioDTO($elem['Armadio_Id']);
            array_push($idArmadi, Mapper::DTO_To_Armadio($dto));
        }
        
        return $idArmadi;
    }
}