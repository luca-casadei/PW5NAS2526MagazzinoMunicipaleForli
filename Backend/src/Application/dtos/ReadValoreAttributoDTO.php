<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadValoreAttributoDTO{
    public int $id;
    public string $valore;
    
    public function __construct(int $id, string $valore){
        $this->id = $id;
        $this->valore = $valore;
    } 
}
?>
