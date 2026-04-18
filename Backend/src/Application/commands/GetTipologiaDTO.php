<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class GetTipologiaDTO{
    public int $tipoId;
    
    public function __construct(int $tipoId){
        $this->tipoId = $tipoId;
    }
}
?>