<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadArtOpEconomicoDTO{
    public string $id;
    public string $descrizione;
    
    public function __construct(string $id, string $descrizione){
        $this->id = $id;
        $this->descrizione = $descrizione;
    }
}
?>
