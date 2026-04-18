<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadQuantitaDTO{
    public int $quantita;
    
    public function __construct(int $quantita){
        $this->quantita = $quantita;
    }
}
?>