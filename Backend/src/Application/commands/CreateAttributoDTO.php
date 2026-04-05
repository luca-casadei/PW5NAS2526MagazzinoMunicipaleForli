<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateAttributoDTO{
    public string $nome;
    public string $valore;
    
    public function __construct(string $nome, string $valore){
        $this->nome = $nome;
        $this->valore = $valore;
    } 
}
?>