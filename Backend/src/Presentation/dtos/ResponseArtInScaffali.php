<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseArtInScaffali{
    public int $armadioId;
    public int $numeroScaffale;
    public int $quantita;
    //public array $articoliInScaffali;
    
    public function __construct(int $armadioId, int $numeroScaffale, int $quantita){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
        $this->quantita = $quantita;
        //$this->articoliInScaffali = $articoliInScaffali;
    }
}
?>