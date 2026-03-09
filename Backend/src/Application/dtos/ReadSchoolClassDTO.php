<?php
declare(strict_types=1);
namespace Backend\Application\dtos;
class ReadSchoolClassDTO {
    public int $id;
    public string $nome;
    public string $link;
    public string $materia;
    public string $respoEmail;

    public function __construct(int $id, string $nome, string $link, string $materia,  string $respoEmail) {
        $this->id = $id;
        $this->nome = $nome;
        $this->link = $link;
        $this->materia = $materia;
        $this->respoEmail = $respoEmail;
    }
}
