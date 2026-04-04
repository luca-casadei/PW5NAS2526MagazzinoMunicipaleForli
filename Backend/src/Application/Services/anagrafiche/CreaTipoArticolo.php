<?php
declare(strict_types=1);
namespace Backend\Application\Services;


use Backend\Application\commands\CreateAttrArtDTO;
use Backend\Application\commands\CreateAttributoDTO;
use Backend\Application\interfaces\repo\IAssociaAttributoRepo;
use Backend\Application\interfaces\repo\ICreaArticoloRepo;
use Backend\Application\interfaces\repo\ICreaAttributoRepo;
use Backend\Application\interfaces\repo\IGetArticoloPerFirmaRepo;
use Backend\Application\interfaces\repo\IGetAttributoByIdRepo;
use Backend\Application\interfaces\serv\ICreaTipoArticolo;
use Exception;


class CreaTipoArticolo implements ICreaTipoArticolo{
    private IAssociaAttributoRepo $associaAttributoRepo;
    private ICreaArticoloRepo $creaArticoloRepo;
    private ICreaAttributoRepo $creaAttributoRepo;
    private IGetArticoloPerFirmaRepo $getArticoloPerFirmaRepo;
    private IGetAttributoByIdRepo $getAttributoByIdRepo;
    public function __construct(IAssociaAttributoRepo $associaAttributoRepo, 
    ICreaArticoloRepo $creaArticoloRepo,
    ICreaAttributoRepo $creaAttributoRepo,
    IGetArticoloPerFirmaRepo $getArticoloPerFirmaRepo,
    IGetAttributoByIdRepo $getAttributoByIdRepo
    ){
        $this->associaAttributoRepo = $associaAttributoRepo;
        $this->creaArticoloRepo = $creaArticoloRepo;
        $this->creaAttributoRepo = $creaAttributoRepo;
        $this->getArticoloPerFirmaRepo = $getArticoloPerFirmaRepo;
        $this->getAttributoByIdRepo = $getAttributoByIdRepo;
    }
    public function execute(int $tipologiaId, string $nome, array $attributi): void {
        
        if (empty($attributi)) {
            throw new Exception("Un Tipo Articolo deve avere almeno un attributo.");
        }

        $attributiConValori = [];
        $attributiIds = [];

        foreach ($attributi as $attr) {
            //unico controllo che si può faare è eliminare spazi e maiuscole o minuscole per non crearne uguali
            $nomeAttr = strtolower(trim($attr->nome));
            // Cerchiamo se la parola esiste già
            $id = $this->getAttributoByIdRepo->findIdByNome($nomeAttr);
            
            if ($id === null) {
                // Se non esiste, lo creiamo
                $id = $this->creaAttributoRepo->creaAttributo($nomeAttr, "");
            }
            $attributiConValori[] = new CreateAttributoDTO($id, $attr->valore);
            $attributiIds[] = $id;
        }

        $articoloEsistenteId = $this->getArticoloPerFirmaRepo->trovaArticolo($nome, $tipologiaId, $attributiIds);

        if ($articoloEsistenteId !== null) {
            // Se esiste, blocchiamo tutto.
            throw new Exception("Errore: Questo Tipo Articolo (Tipologia + Combinazione esatta di Attributi) esiste già.");
        }

        $nuovoArticoloId = $this->creaArticoloRepo->creaArticolo($nome, $tipologiaId);

        foreach ($attributiConValori as $attVal) {
            $this->associaAttributoRepo->associaAttributo(
                new CreateAttrArtDTO($nuovoArticoloId, $attVal->id, $attVal->valore)
            );
        }
    }
}


