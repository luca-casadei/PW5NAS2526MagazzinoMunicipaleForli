<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;
interface ITrovaArticoliByNome
{
    public function getArticoliByNome(string $nome): array;
}