<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo;

use Backend\Application\interfaces\repo\gestioneTipoArticolo\IGetAttributoByIdRepo;
use Backend\Infrastructure\DatabaseConnector;


class GetAttributoByIdRepo implements IGetAttributoByIdRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function findIdByNome(string $nome): ?int {
        $db = $this->connector->get_db();
        
        $query = "SELECT Attributo_Id FROM Attributi_Articoli WHERE Nome = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $stmt->close();
            return null;
        }
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['Attributo_Id'];
    }
}