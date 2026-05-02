<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo;

use Backend\Application\interfaces\repo\gestioneTipoArticolo\IGetArticoloPerFirmaRepo;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Application\dtos\ReadValoreAttributoDTO;

class GetArticoloPerFirmaRepo implements IGetArticoloPerFirmaRepo{
    private DatabaseConnector $connector;
    
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    
    /**
     * @param string $nome
     * @param int $tipologiaId
     * @param ReadValoreAttributoDTO[] $attributiConValori
     * @return int|null
     */
    public function trovaArticolo(string $nome, int $tipologiaId, array $attributiConValori): int|null{
        $numeroAttributi = count($attributiConValori);

        if ($numeroAttributi === 0) {
            return null; 
        }

        $db = $this->connector->get_db();
        
        // (Attributo_Id = ? AND Valore = ?) OR (Attributo_Id = ? AND Valore = ?) et
        $condizioniOR = [];
        foreach ($attributiConValori as $attr) {
            $condizioniOR[] = "(AA.Attributo_Id = ? AND AA.Valore = ?)";
        }
        $stringaCondizioni = implode(' OR ', $condizioniOR);

        // trova art con stesso num di attr
        // + controlla id valore
        $query = "SELECT A.Articolo_Id
                  FROM Articoli A
                  JOIN Attributi_Associati AA ON A.Articolo_Id = AA.Articolo_Id
                  WHERE A.Tipologia_Id = ? AND A.Nome = ?
                  GROUP BY A.Articolo_Id
                  HAVING COUNT(AA.Attributo_Id) = ? 
                     AND SUM($stringaCondizioni) = ?;";

        $stmt = $db->prepare($query);

        // tipi dinamiciper il bind_param
        // 'i' (TipologiaId) + 's' (Nome) + 'i' (Count) + ('is' * N: id e valore) + 'i' (Count finale)
        $tipiParametri = "isi" . str_repeat("is", $numeroAttributi) . "i";
        $valoriDaBindare = [$tipologiaId, $nome, $numeroAttributi];
        
        foreach ($attributiConValori as $attVal) {
            $valoriDaBindare[] = $attVal->id;
            $valoriDaBindare[] = $attVal->valore;
        }
        //questo serve per fare in modo che la somma delle condizioni verificate sia per tutti glia ttributi
        $valoriDaBindare[] = $numeroAttributi; 

        // bind dinamico
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