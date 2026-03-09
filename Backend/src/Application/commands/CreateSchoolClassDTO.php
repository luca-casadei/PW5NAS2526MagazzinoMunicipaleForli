<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateSchoolClassDTO{
    public string $nome;
    public string $materia;
    public string $respoEmail;

    public function __construct(string $nome, string $materia, string $respoEmail) {
        $this->nome = $nome;
        $this->materia = $materia;
        $this->respoEmail = $respoEmail;
    }
}
