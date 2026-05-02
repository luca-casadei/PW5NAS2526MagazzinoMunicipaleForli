<?php
declare(strict_types=1);
namespace Backend\Application\Services\anagrafiche;

use Backend\Application\commands\CreateAttrArtDTO;
use Backend\Application\commands\CreateTipoArticoloDTO;
use Backend\Application\dtos\ReadValoreAttributoDTO;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\IAssociaAttributoRepo;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\ICreaArticoloRepo;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\ICreaAttributoRepo;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\IGetArticoloPerFirmaRepo;
use Backend\Application\interfaces\repo\gestioneTipoArticolo\IGetAttributoByIdRepo;
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
    
    public function execute(CreateTipoArticoloDTO $command): void {
        
        if (empty($command->attributi)) {
            throw new Exception("Un Tipo Articolo deve avere almeno un attributo.");
        }

        $attributiConValori = [];

        foreach ($command->attributi as $attr) {
            $nomeAttr = strtolower(trim($attr->nome));
            
            $id = $this->getAttributoByIdRepo->findIdByNome($nomeAttr);
            
            if ($id === null) {
                $id = $this->creaAttributoRepo->creaAttributo($nomeAttr);
            }
            $attributiConValori[] = new ReadValoreAttributoDTO($id, trim($attr->valore));
        }

        $articoloEsistenteId = $this->getArticoloPerFirmaRepo->trovaArticolo($command->nome, $command->tipologiaId, $attributiConValori);

        if ($articoloEsistenteId !== null) {
            throw new Exception("Errore: Questo Tipo Articolo (Tipologia + Combinazione esatta di Attributi e Valori) esiste già.");
        }

        $nuovoArticoloId = $this->creaArticoloRepo->creaArticolo($command->nome, $command->tipologiaId);

        foreach ($attributiConValori as $attVal) {
            $this->associaAttributoRepo->associaAttributo(
                new CreateAttrArtDTO($nuovoArticoloId, $attVal->id, $attVal->valore)
            );
        }
    }
}