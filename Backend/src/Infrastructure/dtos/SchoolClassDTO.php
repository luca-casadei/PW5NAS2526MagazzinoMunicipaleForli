<?php
declare(strict_types=1);
namespace Backend\Infrastructure\dtos;
class SchoolClassDTO{
    public int $id;
    public string $name;
    public  string $link;
    public string $materia;
    public string $respoEmail;
    
    public function __construct(int $id, string $name, string $link, string $materia, string $respoEmail){
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->materia = $materia;
        $this->respoEmail = $respoEmail;
    }
}
?>