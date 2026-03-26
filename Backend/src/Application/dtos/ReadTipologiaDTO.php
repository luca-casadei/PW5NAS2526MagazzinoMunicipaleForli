<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadTipologiaDTO{
    public int $id;
    public string $nome;
    public string $descrizione;
    
    public function __construct(int $id, string $nome, string $descrizione){
        $this->id = $id;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
    }
}
?>