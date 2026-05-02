<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\ArticoliInScaffali;

interface IAggiornaQtUsataArticoloRepo{
    public function updateQuantita(ArticoliInScaffali $articoliInScaffali): void;
}