<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class DeleteScaffaleDTO{
    public int $armadioId;
    public int $numScaffale;
    
    public function __construct(int $armadioId, int $numScaffale){
        $this->armadioId = $armadioId;
        $this->numScaffale = $numScaffale;
    }
}
?>