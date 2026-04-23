<?php
namespace Backend\Application\response;

final readonly class ResponseMovimentiDTO {
    public function __construct(
        public int $logId,
        public string $dataOraModifica, // Stringa formattata dal DB
        public string $nomeArticolo,    // Sostituisce l'ID dell'articolo
        public string $nomeTipologia,   // Nuova colonna inserita nel log
        public int $numeroScaffale,
        public int $armadioId,
        public int $qtaPrecedente,
        public int $qtaAggiornata,
        public string $attributiSnapshot // Snapshot aggregata dal trigger DB
    ) {}
}