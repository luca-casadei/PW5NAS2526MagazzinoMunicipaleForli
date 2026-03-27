<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadArticoliInScaffaliDTO{
    public int $armadioId;
    public int $numeroScaffale;
    public int $articoloId;
    public int $quantita;
    
    public function __construct(int $armadioId, int $numeroScaffale, int $articoloId, int $quantita){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
        $this->articoloId = $articoloId;
        $this->quantita = $quantita;
    }
}
?>
