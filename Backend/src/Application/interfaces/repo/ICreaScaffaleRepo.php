<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Domain\Entities\Scaffale;


interface ICreaScaffaleRepo{
    public function execute(Scaffale $scaffale): void;
}