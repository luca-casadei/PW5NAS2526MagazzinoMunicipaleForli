<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\ricerca;
use Backend\Application\interfaces\repo\IVediArticoliPerScaffaleRepo;
use Backend\Domain\Entities\Scaffale;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\mapper\Mapper;
use Backend\Infrastructure\dtos\ArticoloDTO;


class VediArticoliPerScaffaleRepo implements IVediArticoliPerScaffaleRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getArticoli(Scaffale $scaffale): array{
        $dto = Mapper::Scaffale_To_DTO($scaffale);
        $db = $this->connector->get_db();
        // Contiamo quante righe hanno questo esatto link
        $query = "SELECT A.Articolo_Id, A.Nome, A.Tipologia_Id 
        FROM Articoli A JOIN Articoli_Scaffali S ON A.Articolo_Id = S.Articolo_Id
        WHERE S.Numero = ? AND S.Armadio_Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $dto->numeroScaffale, $dto->armadioId);
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