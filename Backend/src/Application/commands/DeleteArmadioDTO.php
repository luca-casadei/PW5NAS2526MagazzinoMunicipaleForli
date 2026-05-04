<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class DeleteArmadioDTO{
    public int $id;
    
    public function __construct(int $id){
        $this->id = $id;
    }
}
?>