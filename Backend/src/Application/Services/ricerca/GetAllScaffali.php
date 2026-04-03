<?php
declare(strict_types=1);

namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Application\interfaces\serv\IGetAllScaffali;



class GetAllScaffali implements IGetAllScaffali{
    private IVediArmadiRepo $armadiRepo;

    public function __construct(IVediArmadiRepo $armadiRepo)
{
        $this->armadiRepo = $armadiRepo;
    }
    public function execute(): array {
        $armadi =  $this->armadiRepo->getAllArmadi();
        $scaffali = [];
        foreach($armadi as $armadio){
            $scaffali[] = $this->armadiRepo->getScaffaliArmadioById($armadio->id->valore);
        }
        return $scaffali;

    }
}