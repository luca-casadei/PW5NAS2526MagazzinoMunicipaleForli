<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadFornitoreDTO{
    public string $id;
    
    public function __construct(string $id){
        $this->id = $id;
    }
}
?>
