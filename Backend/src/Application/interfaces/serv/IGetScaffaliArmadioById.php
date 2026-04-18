<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\dtos\ReadArmadioDTO;

interface IGetScaffaliArmadioById{
    public function execute(ReadArmadioDTO $id): array;
}