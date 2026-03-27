<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadAttributoDTO{
    public int $id;
    public string $nome;
    
    public function __construct(int $id, string $nome){
        $this->id = $id;
        $this->nome = $nome;
    } 
}
?>
