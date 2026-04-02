<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IEsportaStoricoRepo;
use Backend\Application\response\ResponseLogMovimentoDTO;
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

        $query = "SELECT Log_Id, Data_Ora_Modifica, Articolo_Id, Numero_Scaffale, Armadio_Id, QtaPrecedente, QtaAggiornata, Attributi
                  FROM Log_Modifiche_Quantita
                  WHERE Data_Ora_Modifica BETWEEN ? AND ?
                  ORDER BY Data_Ora_Modifica DESC;";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $dataInizio, $dataFine);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $logs = [];
        foreach ($result as $res) {
            $logs[] = new ResponseLogMovimentoDTO(
                (int)$res["Log_Id"],
                $res["Data_Ora_Modifica"],
                (int)$res["Articolo_Id"],
                (int)$res["Numero_Scaffale"],
                (int)$res["Armadio_Id"],
                (int)$res["QtaPrecedente"],
                (int)$res["QtaAggiornata"],
                $res["Attributi"]
            );
        }
        return $logs;
    }
}