<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class DeleteTipologiaDTO{
    public string $tipologiaNome;
    
    public function __construct(string $tipologiaNome){
        $this->tipologiaNome = $tipologiaNome;
    }
}
?>