<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
final readonly class ResponseEsportaStorico {
    public function __construct(
        public int $idArticolo,
        public string $nomeTipologia,
        public array $elencoAttributiConValore, // Array aggregato di ["Nome: Valore", ...]
        public int $quantitaNuova,
        public int $quantitaPrec
    ) {}
}