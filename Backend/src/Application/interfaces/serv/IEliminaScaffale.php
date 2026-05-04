<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\DeleteScaffaleDTO;

interface IEliminaScaffale{
    public function eliminaById(DeleteScaffaleDTO $armadio): void;
}