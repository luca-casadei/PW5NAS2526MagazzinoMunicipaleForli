<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Presentation\Controllers\abstracts\AbstractEsportaStoricoController;

// Estendiamo la classe astratta invece di crearla da zero
class EsportaStoricoCsvController extends AbstractEsportaStoricoController {
    protected function formatAndOutput(array $logGrezziDto): void {
        
        // Impostiamo gli header per il download del CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="storico_movimenti.csv"'); // Ho aggiunto gli apici al filename per sicurezza

        $output = fopen('php://output', 'w');

        // Intestazione aggiornata: rimosso IdArticolo, aggiunti Articolo e Tipologia
        fputcsv($output, [
            'Log_Id', 
            'Data_Ora', 
            'Articolo', 
            'Tipologia', 
            'Posizione', 
            'Qta_Precedente', 
            'Qta_Aggiornata', 
            'Dettagli_Snapshot'
        ]);

        foreach ($logGrezziDto as $logDto) {
            fputcsv($output, [
                $logDto->logId,
                $logDto->dataOraModifica,
                $logDto->nomeArticolo,     // Nuovo campo dal DTO
                $logDto->nomeTipologia,    // Nuovo campo dal DTO
                "Armadio $logDto->armadioId / Scaffale $logDto->numeroScaffale", // Riordinato per logica (dal più grande al più piccolo)
                $logDto->qtaPrecedente,
                $logDto->qtaAggiornata,
                $logDto->attributiSnapshot
            ]);
        }
        
        fclose($output);
        exit(); // fine dopo download
    }
}