<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateScaffaleDTO{
    public int $armadioId;
    
    public function __construct(int $armadioId){
        $this->armadioId = $armadioId;
    }
}
?>