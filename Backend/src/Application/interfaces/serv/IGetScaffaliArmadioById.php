<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IGetScaffaliArmadioById{
    public function execute(int $id): ?array;
}