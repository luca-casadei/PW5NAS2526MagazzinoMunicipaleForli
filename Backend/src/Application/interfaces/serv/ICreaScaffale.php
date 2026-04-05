<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

use Backend\Application\commands\CreateScaffaleDTO;


interface ICreaScaffale{
    public function execute(CreateScaffaleDTO $dto): void;
}