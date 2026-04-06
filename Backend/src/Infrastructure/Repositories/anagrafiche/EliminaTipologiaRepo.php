<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche;

use Backend\Application\interfaces\repo\IEliminaTipologiaRepo;
use Backend\Infrastructure\DatabaseConnector;


class EliminaTipologiaRepo implements IEliminaTipologiaRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function eliminaByNome(string $nomeTipo): void{
        $db = $this->connector->get_db();
        $query = "DELETE FROM Tipologie WHERE Nome = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $nomeTipo);
        $stmt->execute();
        $stmt->close();
    }
}