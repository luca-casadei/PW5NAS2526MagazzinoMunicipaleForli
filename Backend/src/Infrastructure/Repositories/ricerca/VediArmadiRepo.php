<?php
declare(strict_types=1);

namespace Backend\Infrastructure\Repositories\ricerca;
use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\ArmadioDTO;

use Backend\Infrastructure\mapper\Mapper;

class VediArmadiRepo implements IVediArmadiRepo{
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
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