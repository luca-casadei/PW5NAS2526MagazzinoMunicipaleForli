<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateArtRegionaleDTO{
    public string $id;
    public string $descrizione;
    
    public function __construct(string $id, string $descrizione){
        $this->id = $id;
        $this->descrizione = $descrizione;
    }
}
?>