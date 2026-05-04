<?php
declare(strict_types=1);
namespace Backend\Application\Services\anagrafiche;

use Backend\Application\commands\DeleteScaffaleDTO;
use Backend\Application\dtos\ReadScaffaleDTO;
use Backend\Application\interfaces\repo\IEliminaScaffaleRepo;
use Backend\Application\interfaces\repo\IVediArticoliPerScaffaleRepo;
use Backend\Application\interfaces\serv\IEliminaScaffale;
use Backend\Application\mappers\ReadMapper;


class EliminaScaffale implements IEliminaScaffale{
    private IEliminaScaffaleRepo $repo;
    private IVediArticoliPerScaffaleRepo $repoArt;
    public function __construct(IEliminaScaffaleRepo $repository, IVediArticoliPerScaffaleRepo $repoArt){
        $this->repo = $repository;
        $this->repoArt = $repoArt;
    }
    public function eliminaById(DeleteScaffaleDTO $scaffale): void{
        $readScaff = new ReadScaffaleDTO(
            $scaffale->armadioId,
            $scaffale->numScaffale
        );
        $scaffaleEnt = ReadMapper::DTO_To_Scaffale($readScaff);
        if(count($this->repoArt->getArticoli($scaffaleEnt)) > 0){
            throw new \Exception("Non è possibile eliminare un scaffale che contiene articoli. Elimina prima gli articoli.");
        }
        $this->repo->elimina($scaffale->armadioId, $scaffale->numScaffale);
    }
}
