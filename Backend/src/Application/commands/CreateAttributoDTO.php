<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateAttributoDTO{
    public int $id;
    public string $valore;
    
    public function __construct(int $id, string $valore){
        $this->id = $id;
        $this->valore = $valore;
    } 
}
?>