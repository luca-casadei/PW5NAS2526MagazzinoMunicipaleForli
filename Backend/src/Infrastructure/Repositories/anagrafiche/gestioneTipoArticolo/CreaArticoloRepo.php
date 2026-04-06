<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo;

use Backend\Application\interfaces\repo\gestioneTipoArticolo\ICreaArticoloRepo;
use Backend\Infrastructure\DatabaseConnector;


class CreaArticoloRepo implements ICreaArticoloRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function creaArticolo(string $nome, int $tipologiaId): int{
        $db = $this->connector->get_db();
        
        $query = "INSERT INTO Articoli (Nome, Tipologia_Id) VALUES (?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $nome, $tipologiaId);
        $stmt->execute();
        
        $nuovoId = (int)$stmt->insert_id;
        $stmt->close();
        return $nuovoId;
    }
}