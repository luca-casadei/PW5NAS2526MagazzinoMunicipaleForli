<?php
namespace Backend\Application\response;
final readonly class ResponseLogMovimentoDTO {
    public function __construct(
        public int $logId,
        public string $dataOraModifica, // Stringa formattata dal DB
        public int $articoloId,
        public int $numeroScaffale,
        public int $armadioId,
        public int $qtaPrecedente,
        public int $qtaAggiornata,
        public string $attributiSnapshot // snapshot aggregata dal trigger DB
    ) {}
}