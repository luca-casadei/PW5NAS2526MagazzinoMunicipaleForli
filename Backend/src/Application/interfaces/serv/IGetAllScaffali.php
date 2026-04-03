<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IGetAllScaffali{
    public function execute(): array;
}