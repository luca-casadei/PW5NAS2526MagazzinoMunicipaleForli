<?php
declare(strict_types=1);
namespace Backend\Application\mappers;

use Backend\Application\commands\CreateArtOpEconomicoDTO;
use Backend\Application\commands\CreateArtRegionaleDTO;
use Backend\Application\commands\CreateAttributoDTO;
use Backend\Application\commands\CreateTipologiaDTO;
use Backend\Domain\Entities\Armadio;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\Entities\Articolo;
use Backend\Domain\Entities\ArtOpEconomico;
use Backend\Domain\Entities\ArtRegionale;
use Backend\Domain\Entities\AttributiAssociati;
use Backend\Domain\Entities\Attributo;
use Backend\Domain\Entities\Fornitore;
use Backend\Domain\Entities\Scaffale;
use Backend\Domain\Entities\Tipologia;
use Backend\Domain\ValueObjects\Armadio\ArmadioId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\ArticoliInScaffaliId;
use Backend\Domain\ValueObjects\ArticoliInScaffali\QuantitaScorta;
use Backend\Domain\ValueObjects\Articolo\ArticoloId;
use Backend\Domain\ValueObjects\ArtOpEconomico\ArtOpEcoDescrizione;
use Backend\Domain\ValueObjects\ArtOpEconomico\ArtOpEcoIdentificativo;
use Backend\Domain\ValueObjects\ArtRegionale\ArtRegIdentificativo;
use Backend\Domain\ValueObjects\ArtRegionale\ArtRegionaleDescrizione;
use Backend\Domain\ValueObjects\AttributiAssociati\AttributiAssociatiId;
use Backend\Domain\ValueObjects\AttributiAssociati\ValoreAttributo;
use Backend\Domain\ValueObjects\Attributo\AttributoId;
use Backend\Domain\ValueObjects\Attributo\AttributoNome;
use Backend\Domain\ValueObjects\Fornitore\FornitoreId;
use Backend\Domain\ValueObjects\Scaffale\NumeroScaffale;
use Backend\Domain\ValueObjects\Scaffale\ScaffaleId;
use Backend\Domain\ValueObjects\Tipologia\TipologiaDescrizione;
use Backend\Domain\ValueObjects\Tipologia\TipologiaId;
use Backend\Domain\ValueObjects\Tipologia\TipologiaNome;
class ReadMapper{
    //TODO

    public static function DTO_To_ArtOpEconomico(CreateArtOpEconomicoDTO $artOpEconomicoDTO):ArtOpEconomico{
        return new ArtOpEconomico(
            new ArtOpEcoIdentificativo($artOpEconomicoDTO->id),
            new ArtOpEcoDescrizione($artOpEconomicoDTO->descrizione)
        );
    }

    public static function DTO_To_ArtRegionale(CreateArtRegionaleDTO $artRegionaleDTO):ArtRegionale{
        return new ArtRegionale(
            new ArtRegIdentificativo($artRegionaleDTO->id),
            new ArtRegionaleDescrizione($artRegionaleDTO->descrizione)
        );
    }

    public static function DTO_To_Attributo(CreateAttributoDTO $attributoDTO):Attributo{
        return new Attributo(
            new AttributoId(0),
            new AttributoNome($attributoDTO->nome)
        );
    }
    public static function DTO_To_Tipologia(CreateTipologiaDTO $tipologiaDTO):Tipologia{
        return new Tipologia(
            new TipologiaId(0),
            new TipologiaNome($tipologiaDTO->nome),
            new TipologiaDescrizione($tipologiaDTO->descrizione)
        );
    }
}
