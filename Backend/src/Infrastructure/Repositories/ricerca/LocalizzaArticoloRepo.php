<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\ricerca;
use Backend\Application\interfaces\repo\ILocalizzaArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\ArticoliInScaffaliDTO;
use Backend\Infrastructure\mapper\Mapper;


class LocalizzaArticoloRepo implements ILocalizzaArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function localizzaById(int $id): array{
        $db = $this->connector->get_db();
        
        $query = "SELECT Numero, Armadio_Id, Quantita FROM Articoli_Scaffali WHERE Articolo_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        $articoliInScaffali = [];
        foreach ($result as $elem) {
            $dto = new ArticoliInScaffaliDTO($elem['Armadio_Id'], $elem['Numero'], $id, $elem['Quantita']);
            array_push($articoliInScaffali, Mapper::DTO_To_ArticoliInScaffali($dto));
        }
        return $articoliInScaffali;
    }
}