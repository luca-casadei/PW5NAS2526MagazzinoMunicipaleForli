<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class ArticoliInScaffaliDTO{
    public int $armadioId;
    public int $numeroScaffale;
    public int $articoloId;
    public int $quantita;
    public int $qtUsata;
    
    public function __construct(int $armadioId, int $numeroScaffale, int $articoloId, int $quantita, int $qtUsata){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
        $this->articoloId = $articoloId;
        $this->quantita = $quantita;
        $this->qtUsata = $qtUsata;
    }
}
?>
