<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\ICreaTipologiaRepo;
use Backend\Domain\Entities\Tipologia;
use Backend\Infrastructure\DatabaseConnector;


class CreaTipologiaRepo implements ICreaTipologiaRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function execute(Tipologia $tipologia): void{
        $db = $this->connector->get_db();
        $query = "INSERT INTO Tipologie (Nome, Descrizione) VALUES (?, ?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $tipologia->nome, $tipologia->descrizione);
        $stmt->execute();
        $stmt->close();
    }
    public function verificaEsistenza(string $nome): bool {
        $db = $this->connector->get_db();
        $query = "SELECT COUNT(*) FROM Tipologie WHERE Nome = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }
}