<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseTipologia{
    public string $nome;
    public string $descrizione;
    
    public function __construct(string $nome, string $descrizione){
        $this->nome = $nome;
        $this->descrizione = $descrizione;
    }
}
?>