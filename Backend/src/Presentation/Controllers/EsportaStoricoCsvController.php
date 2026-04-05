<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Presentation\Controllers\abstracts\AbstractEsportaStoricoController;

// Estendiamo la classe astratta invece di crearla da zero
class EsportaStoricoCsvController extends AbstractEsportaStoricoController {
    protected function formatAndOutput(array $logGrezziDto): void {
        
        // Impostiamo gli header per il download del CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=storico_movimenti.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['Log_Id', 'Data_Ora', 'IdArticolo', 'Scaffale', 'QtaPrecedente', 'QtaAggiornata', 'DettagliSnapshot']);

        foreach ($logGrezziDto as $logDto) {
            fputcsv($output, [
                $logDto->logId,
                $logDto->dataOraModifica,
                $logDto->articoloId,
                "Scaffale $logDto->numeroScaffale / Armadio $logDto->armadioId",
                $logDto->qtaPrecedente,
                $logDto->qtaAggiornata,
                $logDto->attributiSnapshot
            ]);
        }
        
        fclose($output);
        exit(); // fine dopo download
    }
}