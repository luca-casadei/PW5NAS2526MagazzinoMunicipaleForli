<?php
declare(strict_types=1);
namespace Backend\Infrastructure\mapper;

use Backend\Domain\Entities\Armadio;
use Backend\Domain\Entities\Articolo;
use Backend\Domain\Entities\Scaffale;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Backend\Infrastructure\dtos\ArmadioDTO;
use Backend\Infrastructure\dtos\ArticoloDTO;
use Backend\Infrastructure\dtos\ScaffaleDTO;

class Mapper{
    //TODO
    public static function Armadio_To_DTO(Armadio $armadio):ArmadioDTO
    {
        return new ArmadioDTO(
            $armadio->id->valore
        );
    }

    public static function DTO_To_Armadio(ArmadioDTO $armadioDTO):Armadio{
        return new Armadio(
            new ArmadioId($armadioDTO->id)
        );
    }

    public static function Articolo_To_DTO(Articolo $articolo):ArticoloDTO
    {
        return new ArticoloDTO(
            $articolo->id->valore
        );
    }

    public static function DTO_To_Articolo(ArticoloDTO $articoloDTO):Articolo{
        return new Articolo(
            new ArticoloId($articoloDTO->id)
        );
    }

    public static function Scaffale_To_DTO(Scaffale $scaffale):ScaffaleDTO
    {
        return new ScaffaleDTO(
            $scaffale->id->idArmadio,
            $scaffale->id->numScaffale
        );
    }

    public static function DTO_To_Scaffale(ScaffaleDTO $scaffaleDTO):Scaffale{
        return new Scaffale(
            new ScaffaleId($scaffaleDTO->id)
        );
    }
}