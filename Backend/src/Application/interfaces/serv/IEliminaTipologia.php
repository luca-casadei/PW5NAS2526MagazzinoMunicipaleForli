<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\DeleteTipologiaDTO;

interface IEliminaTipologia{
    public function eliminaByNome(DeleteTipologiaDTO $tipo): void;
}