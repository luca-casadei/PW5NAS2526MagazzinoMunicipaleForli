<?php

namespace Backend\Application\response;

// DTO che rappresenta un articolo completo di tutte le informazioni,
// rispecchiando l'aggregazione di più tabelle.
class ResponseArticoloCompletoDTO
{
    public function __construct(
        public int $idArticolo,
        public string $nomeArticolo,
        public int $idTipologia,
        public array $attributi, // Array di DTO AttributoValoreDTO
        public int $quantitaTotale
    ) {}
}