<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateAttributoDTO{
    public string $nome;
    
    public function __construct(string $nome){
        $this->nome = $nome;
    } 
}
?>