<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IVediArticoliRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\mapper\Mapper;
use Backend\Infrastructure\dtos\ArticoloDTO;


class VediArticoliRepo implements IVediArticoliRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getAllArticoli(): array{
        $db = $this->connector->get_db();
        // Contiamo quante righe hanno questo esatto link
        $query = "SELECT Articolo_Id, Nome, Tipologia_Id FROM Articoli";
        $result = $db->query($query);
        $res = $result->fetch_all(MYSQLI_ASSOC);
        $articoli = [];
        foreach ($res as $elem) {
            $dto = new ArticoloDTO($elem['Articolo_Id'], $elem['Nome'], $elem['Tipologia_Id']);
            array_push($articoli, Mapper::DTO_To_Articolo($dto));
        }
        return $articoli;
    }
}