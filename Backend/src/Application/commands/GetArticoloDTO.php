<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class GetArticoloDTO{
    public int $articoloId;
    
    public function __construct(int $articoloId){
        $this->articoloId = $articoloId;
    }
}
?>