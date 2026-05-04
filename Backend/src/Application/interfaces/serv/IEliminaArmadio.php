<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\DeleteArmadioDTO;

interface IEliminaArmadio{
    public function eliminaById(DeleteArmadioDTO $armadio): void;
}