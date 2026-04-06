<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo;

use Backend\Application\interfaces\repo\gestioneTipoArticolo\ICreaAttributoRepo;
use Backend\Infrastructure\DatabaseConnector;


class CreaAttributoRepo implements ICreaAttributoRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function creaAttributo(string $nome, string $descrizione): int {
        $db = $this->connector->get_db();
        
        $query = "INSERT INTO Attributi_Articoli (Nome, Descrizione) VALUES (?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $nome, $descrizione);
        $stmt->execute();

        $nuovoId = (int)$stmt->insert_id; 
        $stmt->close();
        return $nuovoId;
    }
}