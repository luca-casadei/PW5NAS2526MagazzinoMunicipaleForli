<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class ArticoloDTO{
    public int $id;
    public int $tipologiaId;
    
    public function __construct(int $id, int $tipologiaId){
        $this->id = $id;
        $this->tipologiaId = $tipologiaId;
    }
}
?>