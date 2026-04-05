<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseArticoli
{
    public function __construct(
        public int $idArticolo,
        public string $nomeArticolo,
        public string $nomeTipologia,
        public array $attributi, // Array di DTO AttributoValoreDTO
        public int $quantitaTotale
    ) {}
}