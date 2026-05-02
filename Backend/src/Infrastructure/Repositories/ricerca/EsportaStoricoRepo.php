<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\ricerca;

use Backend\Application\interfaces\repo\IEsportaStoricoRepo;
use Backend\Application\response\ResponseMovimentiDTO;
use Backend\Infrastructure\DatabaseConnector;

class EsportaStoricoRepo implements IEsportaStoricoRepo
{
    private DatabaseConnector $connector;
    
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
    public function getMovimentiByDateRange(string $dataInizio, string $dataFine): array
    {
        $db = $this->connector->get_db();

        $query = "SELECT Log_Id, Data_Ora_Modifica, Nome_Articolo, Nome_Tipologia, Numero_Scaffale, Armadio_Id, QtaPrecedente, QtaAggiornata, QtaUsataPrecedente, QtaUsataAggiornata, Attributi
                  FROM Log_Modifiche_Quantita
                  WHERE Data_Ora_Modifica BETWEEN ? AND ?
                  ORDER BY Data_Ora_Modifica DESC;";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $dataInizio, $dataFine);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $logs = [];
        
        foreach ($result as $res) {
            $logs[] = new ResponseMovimentiDTO(
                (int)$res["Log_Id"],
                $res["Data_Ora_Modifica"],
                $res["Nome_Articolo"],
                $res["Nome_Tipologia"],
                (int)$res["Numero_Scaffale"],
                (int)$res["Armadio_Id"],
                (int)$res["QtaPrecedente"],
                (int)$res["QtaAggiornata"],
                (int)$res["QtaUsataPrecedente"], 
                (int)$res["QtaUsataAggiornata"], 
                $res["Attributi"]
            );
        }
        
        return $logs;
    }
}