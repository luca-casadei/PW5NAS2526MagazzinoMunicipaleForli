<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Presentation\Controllers\abstracts\AbstractEsportaStoricoController;

// Estendiamo la classe astratta invece di crearla da zero
class EsportaStoricoCsvController extends AbstractEsportaStoricoController {
    protected function formatAndOutput(array $logGrezziDto, bool $errore): void {
        
        // Impostiamo gli header per il download del CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="storico_movimenti.csv"'); // Ho aggiunto gli apici al filename per sicurezza

        $output = fopen('php://output', 'w');

        if(empty($logGrezziDto))
        {
            fputcsv($output, [
                'Risposta'
            ]);
            fputcsv($output, [
                    "Nessun movimento presente nell'ultimo anno"
                ]
            );
        }
        elseif($errore)
        {
            fputcsv($output, [
                'Log_Id', 
                'Data_Ora', 
                'Articolo', 
                'Tipologia', 
                'Posizione', 
                'Qta_Precedente',
                'Qta_Aggiornata',
                'Qta_Usata_Prec',
                'Qta_Usata_Agg',
                'Dettagli_Snapshot'
            ]);

            foreach ($logGrezziDto as $logDto) {
                fputcsv($output, [
                    $logDto->logId,
                    $logDto->dataOraModifica,
                    $logDto->nomeArticolo,     
                    $logDto->nomeTipologia,    
                    "Armadio $logDto->armadioId / Scaffale $logDto->numeroScaffale", 
                    $logDto->qtaPrecedente,
                    $logDto->qtaAggiornata,
                    $logDto->qtaUsataPrecedente, 
                    $logDto->qtaUsataAggiornata, 
                    $logDto->attributiSnapshot
                ]);
            }
        }
        else
        {
            fputcsv($output, [
                'Log_Id', 
                'Data_Ora', 
                'Articolo', 
                'Tipologia', 
                'Posizione', 
                'Qta_Precedente', 
                'Qta_Aggiornata', 
                'Qta_Usata_Prec',    
                'Qta_Usata_Agg',     
                'Dettagli_Snapshot'
            ]);

            foreach ($logGrezziDto as $logDto) {
                fputcsv($output, [
                    $logDto->logId,
                    $logDto->dataOraModifica,
                    $logDto->nomeArticolo,     
                    $logDto->nomeTipologia,    
                    "Armadio $logDto->armadioId / Scaffale $logDto->numeroScaffale", 
                    $logDto->qtaPrecedente,
                    $logDto->qtaAggiornata,
                    $logDto->qtaUsataPrecedente, 
                    $logDto->qtaUsataAggiornata, 
                    $logDto->attributiSnapshot
                ]);
            }
        }
        
        fclose($output);
        exit(); // fine dopo download
    }
}