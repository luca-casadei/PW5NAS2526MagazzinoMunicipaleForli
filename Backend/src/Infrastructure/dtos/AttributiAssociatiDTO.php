<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class AttributiAssociatiDTO{
    public int $articoloId;
    public int $attributoId;
    public string $valore;
    
    public function __construct(int $articoloId, int $attributoId, string $valore){
        $this->articoloId = $articoloId;
        $this->attributoId = $attributoId;
        $this->valore = $valore;
    }
}
?>