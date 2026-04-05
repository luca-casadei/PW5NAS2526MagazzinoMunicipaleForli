<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\ICreaScaffaleRepo;
use Backend\Domain\Entities\Scaffale;
use Backend\Infrastructure\DatabaseConnector;


class CreaScaffaleRepo implements ICreaScaffaleRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function execute(Scaffale $scaffale): void
    {
        $db = $this->connector->get_db();
        $query = "INSERT INTO Scaffali (armadio_id) VALUES (?);";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $scaffale->id->idArmadio->valore);
        $stmt->execute();
        $stmt->close();
    }
}