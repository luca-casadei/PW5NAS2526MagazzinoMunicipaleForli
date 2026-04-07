<?php
declare(strict_types=1);
namespace Backend\Application\mappers;

use Backend\Application\dtos\ReadArmadioDTO;
use Backend\Application\dtos\ReadArticoliInScaffaliDTO;
use Backend\Application\dtos\ReadArticoloDTO;
use Backend\Application\dtos\ReadArtOpEconomicoDTO;
use Backend\Application\dtos\ReadArtRegionaleDTO;
use Backend\Application\dtos\ReadAttributiAssociatiDTO;
use Backend\Application\dtos\ReadAttributoDTO;
use Backend\Application\dtos\ReadFornitoreDTO;
use Backend\Application\dtos\ReadScaffaleDTO;
use Backend\Application\dtos\ReadTipologiaDTO;
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
use Backend\Domain\ValueObjects\Articolo\ArticoloNome;
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
    public static function Armadio_To_DTO(Armadio $armadio):ReadArmadioDTO
    {
        return new ReadArmadioDTO(
            $armadio->id->valore
        );
    }

    public static function DTO_To_Armadio(ReadArmadioDTO $armadioDTO):Armadio{
        return new Armadio(
            new ArmadioId($armadioDTO->id)
        );
    }

    public static function Articolo_To_DTO(Articolo $articolo):ReadArticoloDTO
    {
        return new ReadArticoloDTO(
            $articolo->id->valore,
            $articolo->nome->valore,
            $articolo->tipologiaId->valore
        );
    }

    public static function DTO_To_Articolo(ReadArticoloDTO $articoloDTO):Articolo{
        return new Articolo(
            new ArticoloId($articoloDTO->id),
            new ArticoloNome($articoloDTO->nome),
            new TipologiaId($articoloDTO->tipologiaId)
        );
    }

    public static function Scaffale_To_DTO(Scaffale $scaffale):ReadScaffaleDTO
    {
        return new ReadScaffaleDTO(
            $scaffale->id->idArmadio->valore,
            $scaffale->id->numScaffale->numero
        );
    }

    public static function DTO_To_Scaffale(ReadScaffaleDTO $scaffaleDTO):Scaffale{
        return new Scaffale(
            new ScaffaleId(
                new ArmadioId($scaffaleDTO->armadioId),
                new NumeroScaffale($scaffaleDTO->numeroScaffale)
            )
        );
    }
    public static function ArticoliInScaffali_To_DTO(ArticoliInScaffali $articoliInScaffali):ReadArticoliInScaffaliDTO
    {
        return new ReadArticoliInScaffaliDTO(
            $articoliInScaffali->id->idScaffale->idArmadio->valore,
            $articoliInScaffali->id->idScaffale->numScaffale->numero,
            $articoliInScaffali->id->idArticolo->valore,
            $articoliInScaffali->quantita->valore
        );
    }

    public static function DTO_To_ArticoliInScaffali(ReadArticoliInScaffaliDTO $articoliInScaffaliDTO):ArticoliInScaffali{
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
    public static function ArtOpEconomico_To_DTO(ArtOpEconomico $artOpEconomico):ReadArtOpEconomicoDTO
    {
        return new ReadArtOpEconomicoDTO(
            $artOpEconomico->id->valore,
            $artOpEconomico->descrizione->descrizione
        );
    }

    public static function DTO_To_ArtOpEconomico(ReadArtOpEconomicoDTO $artOpEconomicoDTO):ArtOpEconomico{
        return new ArtOpEconomico(
            new ArtOpEcoIdentificativo($artOpEconomicoDTO->id),
            new ArtOpEcoDescrizione($artOpEconomicoDTO->descrizione)
        );
    }
    public static function ArtRegionale_To_DTO(ArtRegionale $artRegionale):ReadArtRegionaleDTO
    {
        return new ReadArtRegionaleDTO(
            $artRegionale->id->valore,
            $artRegionale->descrizione->descrizione
        );
    }

    public static function DTO_To_ArtRegionale(ReadArtRegionaleDTO $artRegionaleDTO):ArtRegionale{
        return new ArtRegionale(
            new ArtRegIdentificativo($artRegionaleDTO->id),
            new ArtRegionaleDescrizione($artRegionaleDTO->descrizione)
        );
    }
    public static function AttributiAssociati_To_DTO(AttributiAssociati $attributiAssociati):ReadAttributiAssociatiDTO
    {
        return new ReadAttributiAssociatiDTO(
            $attributiAssociati->id->idArticolo->valore,
            $attributiAssociati->id->idAttributo->valore,
            $attributiAssociati->valore->valore
        );
    }

    public static function DTO_To_AttributiAssociati(ReadAttributiAssociatiDTO $attributiAssociatiDTO):AttributiAssociati{
        return new AttributiAssociati(
            new AttributiAssociatiId(
                new ArticoloId($attributiAssociatiDTO->articoloId),
                new AttributoId($attributiAssociatiDTO->attributoId)
            ),
            new ValoreAttributo($attributiAssociatiDTO->valore)
        );
    }
    public static function Attributo_To_DTO(Attributo $attributo):ReadAttributoDTO
    {
        return new ReadAttributoDTO(
            $attributo->id->valore,
            $attributo->nome->nome
        );
    }

    public static function DTO_To_Attributo(ReadAttributoDTO $attributoDTO):Attributo{
        return new Attributo(
            new AttributoId($attributoDTO->id),
            new AttributoNome($attributoDTO->nome)
        );
    }
    public static function Fornitore_To_DTO(Fornitore $fornitore):ReadFornitoreDTO
    {
        return new ReadFornitoreDTO(
            $fornitore->id->ragioneSociale
        );
    }

    public static function DTO_To_Fornitore(ReadFornitoreDTO $fornitoreDTO):Fornitore{
        return new Fornitore(
            new FornitoreId($fornitoreDTO->id)
        );
    }
    public static function Tipologia_To_DTO(Tipologia $tipologia):ReadTipologiaDTO
    {
        return new ReadTipologiaDTO(
            $tipologia->id->valore,
            $tipologia->nome->nome,
            $tipologia->descrizione->descrizione
        );
    }

    public static function DTO_To_Tipologia(ReadTipologiaDTO $tipologiaDTO):Tipologia{
        return new Tipologia(
            new TipologiaId($tipologiaDTO->id),
            new TipologiaNome($tipologiaDTO->nome),
            new TipologiaDescrizione($tipologiaDTO->descrizione)
        );
    }
}
