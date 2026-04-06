<?php
declare(strict_types=1);

namespace Backend\Application\Services\ricerca;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Application\interfaces\repo\IVediScaffaliRepo;
use Backend\Application\interfaces\serv\IGetAllScaffali;

class GetAllScaffali implements IGetAllScaffali{
    private IVediArmadiRepo $armadiRepo;
    private IVediScaffaliRepo $scaffaliRepo;

    public function __construct(IVediArmadiRepo $armadiRepo, IVediScaffaliRepo $scaffaliRepo)
{
        $this->armadiRepo = $armadiRepo;
        $this->scaffaliRepo = $scaffaliRepo;
    }
    public function execute(): array {
        $armadi =  $this->armadiRepo->getAllArmadi();
        $scaffali = [];
        foreach($armadi as $armadio){
            array_push($scaffali, ...$this->scaffaliRepo->getScaffaliArmadioById($armadio->id->valore));
        }
        return $scaffali;
    }
}