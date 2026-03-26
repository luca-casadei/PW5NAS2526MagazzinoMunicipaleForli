<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class ArmadioDTO{
    public int $id;
    
    public function __construct(int $id){
        $this->id = $id;
    }
}
?>