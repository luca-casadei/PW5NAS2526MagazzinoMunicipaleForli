<?php
declare(strict_types=1);
namespace Backend\Application\Services\anagrafiche;

use Backend\Application\commands\DeleteArmadioDTO;
use Backend\Application\interfaces\repo\IEliminaArmadioRepo;
use Backend\Application\interfaces\repo\IVediScaffaliRepo;
use Backend\Application\interfaces\serv\IEliminaArmadio;

class EliminaArmadio implements IEliminaArmadio{
    private IEliminaArmadioRepo $repo;
    private IVediScaffaliRepo $repoScaff;
    public function __construct(IEliminaArmadioRepo $repository, IVediScaffaliRepo $repoScaff){
        $this->repo = $repository;
        $this->repoScaff = $repoScaff;
    }
    public function eliminaById(DeleteArmadioDTO $armadio): void{
        if(count($this->repoScaff->getScaffaliArmadioById($armadio->id)) > 0){
            throw new \Exception("Non è possibile eliminare un armadio che contiene scaffali. Elimina prima gli scaffali.");
        }
        $this->repo->elimina($armadio->id);
    }
}
