<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class UpdateDimQuantitaDTO{
    public function __construct(
        public int $articoloId,
        public int $numScaffale,
        public int $armadioId)
    {}
}
?>