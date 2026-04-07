<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadArticoloDTO{
    public int $id;
    public string $nome;
    public int $tipologiaId;
    
    public function __construct(int $id, string $nome, int $tipologiaId){
        $this->id = $id;
        $this->nome = $nome;
        $this->tipologiaId = $tipologiaId;
    }
}
?>