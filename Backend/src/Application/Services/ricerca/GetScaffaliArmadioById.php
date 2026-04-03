<?php
declare(strict_types=1);

namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\IVediArmadiRepo;
use Backend\Application\interfaces\serv\IGetScaffaliArmadioById;



class GetScaffaliArmadioById implements IGetScaffaliArmadioById{
    private IVediArmadiRepo $armadiRepo;

    public function __construct(IVediArmadiRepo $armadiRepo)
{
        $this->armadiRepo = $armadiRepo;
    }
    public function execute(int $id): ?array {
        return $this->armadiRepo->getScaffaliArmadioById($id);
    }
}