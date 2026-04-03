<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IEliminaTipologia{
    public function eliminaByNome(string $nomeTipo): void;
}