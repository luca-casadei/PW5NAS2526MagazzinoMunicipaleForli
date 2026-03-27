<?php
declare(strict_types=1);
namespace Backend\Infrastructure\mapper;

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
use Backend\Infrastructure\dtos\ArmadioDTO;
use Backend\Infrastructure\dtos\ArticoliInScaffaliDTO;
use Backend\Infrastructure\dtos\ArticoloDTO;
use Backend\Infrastructure\dtos\ArtOpEconomicoDTO;
use Backend\Infrastructure\dtos\ArtRegionaleDTO;
use Backend\Infrastructure\dtos\AttributiAssociatiDTO;
use Backend\Infrastructure\dtos\AttributoDTO;
use Backend\Infrastructure\dtos\FornitoreDTO;
use Backend\Infrastructure\dtos\ScaffaleDTO;
use Backend\Infrastructure\dtos\TipologiaDTO;

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
            $scaffale->id->idArmadio->valore,
            $scaffale->id->numScaffale->numero
        );
    }

    public static function DTO_To_Scaffale(ScaffaleDTO $scaffaleDTO):Scaffale{
        return new Scaffale(
            new ScaffaleId(
                new ArmadioId($scaffaleDTO->armadioId),
                new NumeroScaffale($scaffaleDTO->numeroScaffale)
            )
        );
    }
    public static function ArticoliInScaffali_To_DTO(ArticoliInScaffali $articoliInScaffali):ArticoliInScaffaliDTO
    {
        return new ArticoliInScaffaliDTO(
            $articoliInScaffali->id->idScaffale->idArmadio->valore,
            $articoliInScaffali->id->idScaffale->numScaffale->numero,
            $articoliInScaffali->id->idArticolo->valore,
            $articoliInScaffali->quantita->valore
        );
    }

    public static function DTO_To_ArticoliInScaffali(ArticoliInScaffaliDTO $articoliInScaffaliDTO):ArticoliInScaffali{
        return new ArticoliInScaffali(
            new ArticoliInScaffaliId(
                new ArticoloId($articoliInScaffaliDTO->articoloId),
                new ScaffaleId(
                    new ArmadioId($articoliInScaffaliDTO->armadioId),
                    new NumeroScaffale($articoliInScaffaliDTO->numeroScaffale)
                )
                ),
            new QuantitaScorta($articoliInScaffaliDTO->quantita)
        );
    }
    public static function ArtOpEconomico_To_DTO(ArtOpEconomico $artOpEconomico):ArtOpEconomicoDTO
    {
        return new ArtOpEconomicoDTO(
            $artOpEconomico->id->valore,
            $artOpEconomico->descrizione->descrizione
        );
    }

    public static function DTO_To_ArtOpEconomico(ArtOpEconomicoDTO $artOpEconomicoDTO):ArtOpEconomico{
        return new ArtOpEconomico(
            new ArtOpEcoIdentificativo($artOpEconomicoDTO->id),
            new ArtOpEcoDescrizione($artOpEconomicoDTO->descrizione)
        );
    }
    public static function ArtRegionale_To_DTO(ArtRegionale $artRegionale):ArtRegionaleDTO
    {
        return new ArtRegionaleDTO(
            $artRegionale->id->valore,
            $artRegionale->descrizione->descrizione
        );
    }

    public static function DTO_To_ArtRegionale(ArtRegionaleDTO $artRegionaleDTO):ArtRegionale{
        return new ArtRegionale(
            new ArtRegIdentificativo($artRegionaleDTO->id),
            new ArtRegionaleDescrizione($artRegionaleDTO->descrizione)
        );
    }
    public static function AttributiAssociati_To_DTO(AttributiAssociati $attributiAssociati):AttributiAssociatiDTO
    {
        return new AttributiAssociatiDTO(
            $attributiAssociati->id->idArticolo->valore,
            $attributiAssociati->id->idAttributo->valore,
            $attributiAssociati->valore->valore
        );
    }

    public static function DTO_To_AttributiAssociati(AttributiAssociatiDTO $attributiAssociatiDTO):AttributiAssociati{
        return new AttributiAssociati(
            new AttributiAssociatiId(
                new ArticoloId($attributiAssociatiDTO->articoloId),
                new AttributoId($attributiAssociatiDTO->attributoId)
            ),
            new ValoreAttributo($attributiAssociatiDTO->valore)
        );
    }
    public static function Attributo_To_DTO(Attributo $attributo):AttributoDTO
    {
        return new AttributoDTO(
            $attributo->id->valore,
            $attributo->nome->nome
        );
    }

    public static function DTO_To_Attributo(AttributoDTO $attributoDTO):Attributo{
        return new Attributo(
            new AttributoId($attributoDTO->id),
            new AttributoNome($attributoDTO->nome)
        );
    }
    public static function Fornitore_To_DTO(Fornitore $fornitore):FornitoreDTO
    {
        return new FornitoreDTO(
            $fornitore->id->ragioneSociale
        );
    }

    public static function DTO_To_Fornitore(FornitoreDTO $fornitoreDTO):Fornitore{
        return new Fornitore(
            new FornitoreId($fornitoreDTO->id)
        );
    }
    public static function Tipologia_To_DTO(Tipologia $tipologia):TipologiaDTO
    {
        return new TipologiaDTO(
            $tipologia->id->valore,
            $tipologia->nome->nome,
            $tipologia->descrizione->descrizione
        );
    }

    public static function DTO_To_Tipologia(TipologiaDTO $tipologiaDTO):Tipologia{
        return new Tipologia(
            new TipologiaId($tipologiaDTO->id),
            new TipologiaNome($tipologiaDTO->nome),
            new TipologiaDescrizione($tipologiaDTO->descrizione)
        );
    }
}