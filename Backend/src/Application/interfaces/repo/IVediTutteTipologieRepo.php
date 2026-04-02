<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

interface IVediTutteTipologieRepo{
    public function getAllTipologie(): array;
}