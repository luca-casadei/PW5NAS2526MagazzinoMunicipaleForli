<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseArtInScaffali{
    public int $armadioId;
    public int $numeroScaffale;
    public int $quantita;
    public int $qtUsata;
    //public array $articoliInScaffali;
    
    public function __construct(int $armadioId, int $numeroScaffale, int $quantita, int $qtUsata){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
        $this->quantita = $quantita;
        $this->qtUsata = $qtUsata;
        //$this->articoliInScaffali = $articoliInScaffali;
    }
}
?>