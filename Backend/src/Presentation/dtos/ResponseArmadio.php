<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseArmadio{
    public int $id;
    public array $scaffali;
    
    public function __construct(int $id, array $scaffali){
        $this->id = $id;
        $this->scaffali= $scaffali;
    }
}
?>