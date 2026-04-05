<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseTipologia{
    public string $nome;
    
    public function __construct(string $nome){
        $this->nome = $nome;
    }
}
?>