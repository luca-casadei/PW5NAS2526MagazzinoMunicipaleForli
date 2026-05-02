<?php
declare(strict_types=1);
namespace Backend\Application\commands;
class UpdateAggQuantitaUsataDTO{
    public function __construct(
        public int $articoloId,
        public int $numScaffale,
        public int $armadioId,
        public int $quantitaAgg,
        )
    {}
}
?>