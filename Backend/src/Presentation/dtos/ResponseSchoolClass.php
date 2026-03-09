<?php
declare(strict_types=1);
namespace Backend\Presentation\dtos;
class ResponseSchoolClass
{
    //TODO
    public string $nome;
    public string $link;
    public string $materia;
    public string $respoEmail;
    public string $respoFullName;
    public function __construct(string $nome, string $link, string $materia,  string $respoEmail, string $respoFullName) {
        $this->nome = $nome;
        $this->link = $link;
        $this->materia = $materia;
        $this->respoEmail = $respoEmail;
        $this->respoFullName = $respoFullName;
    }
}