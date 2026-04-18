<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\GetArtNomeDTO;
interface ITrovaArticoliByNome
{
    public function getArticoliByNome(GetArtNomeDTO $nome): array;
}