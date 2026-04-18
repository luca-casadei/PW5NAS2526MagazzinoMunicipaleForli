<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class DeleteTipoArticoloDTO{
    public int $articoloId;
    
    public function __construct(int $articoloId){
        $this->articoloId = $articoloId;
    }
}
?>