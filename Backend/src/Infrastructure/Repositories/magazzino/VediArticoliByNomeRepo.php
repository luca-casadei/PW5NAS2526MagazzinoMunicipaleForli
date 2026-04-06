<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;
use Backend\Application\interfaces\repo\IVediArticoliByNomeRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\mapper\Mapper;
use Backend\Infrastructure\dtos\ArticoloDTO;


class VediArticoliByNomeRepo implements IVediArticoliByNomeRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getArticoliByNome(string $nome): array{
        $db = $this->connector->get_db();
        // Contiamo quante righe hanno questo esatto link
        $query = "SELECT Articolo_Id, Nome, Tipologia_Id FROM Articoli WHERE Nome = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_all(MYSQLI_ASSOC);
        $articoli = [];
        foreach ($res as $elem) {
            $dto = new ArticoloDTO($elem['Articolo_Id'], $elem['Nome'], $elem['Tipologia_Id']);
            array_push($articoli, Mapper::DTO_To_Articolo($dto));
        }
        return $articoli;
    }
}