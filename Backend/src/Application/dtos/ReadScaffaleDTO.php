<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadScaffaleDTO{
    public int $armadioId;
    public int $numeroScaffale;
    
    public function __construct(int $armadioId, int $numeroScaffale){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
    }
}
?>