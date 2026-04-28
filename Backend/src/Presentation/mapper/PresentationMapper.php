<?php
declare(strict_types=1);
namespace Backend\Presentation\mapper;
use Backend\Domain\Entities\Armadio;
use Backend\Domain\Entities\ArticoliInScaffali;
use Backend\Domain\Entities\Scaffale;
use Backend\Domain\Entities\Tipologia;
use Backend\Presentation\dtos\ResponseArmadio;
use Backend\Presentation\dtos\ResponseArtInScaffali;
use Backend\Presentation\dtos\ResponseScaffale;
use Backend\Presentation\dtos\ResponseTipologia;
class PresentationMapper {
    //TODO
    public static function tipologia_to_ResponseTipologia(Tipologia $tipologia): ResponseTipologia {
        return new ResponseTipologia(
            $tipologia->id->valore,
            $tipologia->nome->nome
        );
    }
    public static function armadio_to_ResponseArmadio(Armadio $armadio): ResponseArmadio {
        return new ResponseArmadio(
            $armadio->id->valore
        );
    }
    public static function scaffale_to_ResponseScaffale(Scaffale $scaffale): ResponseScaffale {
        return new ResponseScaffale(
            $scaffale->id->idArmadio->valore,
            $scaffale->id->numScaffale->numero
        );
    }
    public static function artInScaffali_to_ResponseArtInScaffali(ArticoliInScaffali $art): ResponseArtInScaffali {
        return new ResponseArtInScaffali(
            $art->id->idScaffale->idArmadio->valore,
            $art->id->idScaffale->numScaffale->numero,
            $art->quantita->valore
        );
    }
}
