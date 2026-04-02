<?php
declare(strict_types=1);
namespace Backend\Application\response;
class ResponseAttributoConValoreDTO{
    public int $id;
    public string $nome;
    public string $valore;
    
    public function __construct(int $id, string $nome, string $valore){
        $this->id = $id;
        $this->nome = $nome;
        $this->valore = $valore;
    } 
}
?>