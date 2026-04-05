<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseScaffale{
    public int $armadioId;
    public int $numeroScaffale;
    //public array $articoliInScaffali;
    
    public function __construct(int $armadioId, int $numeroScaffale){
        $this->armadioId = $armadioId;
        $this->numeroScaffale = $numeroScaffale;
        //$this->articoliInScaffali = $articoliInScaffali;
    }
}
?>