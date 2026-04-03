<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;

use Backend\Application\interfaces\repo\IGetArticoloPerFirmaRepo;
use Backend\Infrastructure\DatabaseConnector;


class GetArticoloPerFirmaRepo implements IGetArticoloPerFirmaRepo{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function trovaArticolo(string $nome, int $tipologiaId, array $attributiIds): int|null{
        $numeroAttributi = count($attributiIds);

        // Se l'array è vuoto, restituiamo null (il service blocca questo caso a monte, ma è buona pratica proteggere il repo)
        if ($numeroAttributi === 0) {
            return null; 
        }

        $db = $this->connector->get_db();
        // Se ho 3 attributi, genera "?,?,?". Se ne ho 5, genera "?,?,?,?,?".
        $placeholders = implode(',', array_fill(0, $numeroAttributi, '?'));

        $query = "SELECT A.Articolo_Id
                  FROM Articoli A
                  JOIN Attributi_Associati AA ON A.Articolo_Id = AA.Articolo_Id
                  WHERE A.Tipologia_Id = ? AND A.Nome = ?
                  GROUP BY A.Articolo_Id
                  HAVING COUNT(AA.Attributo_Id) = ? 
                     AND SUM(AA.Attributo_Id IN ($placeholders)) = ?;";

        $stmt = $db->prepare($query);

        // 3. GENERIAMO I TIPI E I PARAMETRI DINAMICAMENTE PER IL BIND_PARAM
        // I tipi: 'i' (TipologiaId) + 's' (Nome) + 'i' (Count) + 'i'*N (gli ID dell'array) + 'i' (Count finale)
        $tipiParametri = "isi" . str_repeat("i", $numeroAttributi) . "i";
        // Costruiamo l'array sequenziale dei valori da passare
        $valoriDaBindare = [$tipologiaId, $nome, $numeroAttributi];
        foreach ($attributiIds as $id) {
            $valoriDaBindare[] = $id;
        }
        $valoriDaBindare[] = $numeroAttributi; // L'ultimo '?' dell'HAVING

        // Usiamo lo "Splat Operator" (...) per passare dinamicamente l'array al bind_param
        $stmt->bind_param($tipiParametri, ...$valoriDaBindare);
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return null; // La firma non esiste
        }
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['Articolo_Id'];
    }
}