<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\ricerca;
use Backend\Application\interfaces\repo\IVediScaffaliRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\ScaffaleDTO;
use Backend\Infrastructure\mapper\Mapper;


class VediScaffaliRepo implements IVediScaffaliRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getScaffaliArmadioById(int $id): array{
        $db = $this->connector->get_db();
        
        $query = "SELECT Numero FROM Scaffali WHERE Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        $numeriScaffali = [];
        foreach ($result as $elem) {
            $dto = new ScaffaleDTO($id, $elem['Numero']);
            array_push($numeriScaffali, Mapper::DTO_To_Scaffale($dto));
        }
        return $numeriScaffali;
    }
}