<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories\magazzino;

use Backend\Application\interfaces\repo\IAggiornaQtArticoloRepo;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\mapper\Mapper;

class AggQtArticoloRepo implements IAggiornaQtArticoloRepo {
    private DatabaseConnector $connector;

    public function __construct(DatabaseConnector $connector) {
        $this->connector = $connector;
    }

    public function updateQuantita(ArticoliInScaffali $articoliInScaffali): void
    {
        $dto = Mapper::ArticoliInScaffali_To_DTO($articoliInScaffali);
        // 1. Estraiamo i valori in variabili locali per evitare l'errore del bind_param con le proprietà readonly
        $quantita = $dto->quantita;
        $articoloId = $dto->articoloId;
        $numScaffale = $dto->numeroScaffale;
        $armadioId = $dto->armadioId;

        // 2. Controllo presenza ed eventuale creazione
        if (!$this->verificaPresenza($articoloId, $numScaffale, $armadioId)) {
            $this->creaPresenza($articoloId, $numScaffale, $armadioId);
        }
        if($quantita == 0){
            $this->eliminaPresenza($articoloId, $numScaffale, $armadioId);
            return;
        }
        // 3. Aggiornamento effettivo
        $db = $this->connector->get_db();
        $query = "UPDATE Articoli_Scaffali
                  SET Quantita = ? 
                  WHERE Articolo_Id = ? 
                    AND Numero = ? 
                    AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iiii", $quantita, $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Verifica se esiste già un record per questo articolo in questo specifico scaffale.
     */
    private function verificaPresenza(int $articoloId, int $numScaffale, int $armadioId): bool
    {
        $db = $this->connector->get_db();
        $query = "SELECT COUNT(*) FROM Articoli_Scaffali WHERE Articolo_Id = ? AND Numero = ? AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count > 0;
    }

    /**
     * Crea l'associazione base impostando la quantità iniziale a 0.
     */
    private function creaPresenza(int $articoloId, int $numScaffale, int $armadioId): void
    {
        $db = $this->connector->get_db();
        $query = "INSERT INTO Articoli_Scaffali (Articolo_Id, Numero, Armadio_Id, Quantita) VALUES (?, ?, ?, 0);";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }
    private function eliminaPresenza(int $articoloId, int $numScaffale, int $armadioId): void
    {
        $db = $this->connector->get_db();
        $query = "DELETE FROM Articoli_Scaffali WHERE Articolo_Id = ? AND Numero = ? AND Armadio_Id = ?;";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("iii", $articoloId, $numScaffale, $armadioId);
        $stmt->execute();
        $stmt->close();
    }
}