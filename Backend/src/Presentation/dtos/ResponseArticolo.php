<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseArticolo{
    public int $id;
    public array $attributiAssociati;
    
    public function __construct(int $id, array $attributiAssociati){
        $this->id = $id;
        $this->attributiAssociati= $attributiAssociati;
    }
}
?>