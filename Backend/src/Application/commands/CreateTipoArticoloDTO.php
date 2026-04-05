<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class CreateTipoArticoloDTO{
    
    public function __construct(public int $tipologiaId,
        public string $nome,
        public array $attributi)
    {}
}
?>