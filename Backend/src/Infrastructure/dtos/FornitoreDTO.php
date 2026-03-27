<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class FornitoreDTO{
    public string $id;
    
    public function __construct(string $id){
        $this->id = $id;
    }
}
?>