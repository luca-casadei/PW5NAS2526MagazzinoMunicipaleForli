<?php
declare(strict_types=1);

namespace Backend\Application\Services\ricerca;

use Backend\Application\interfaces\serv\IGetScaffaliArmadioById;
use Backend\Application\interfaces\repo\IVediScaffaliRepo;

class GetScaffaliArmadioById implements IGetScaffaliArmadioById{
    private IVediScaffaliRepo $scaffaliRepo;

    public function __construct(IVediScaffaliRepo $scaffaliRepo)
{
        $this->scaffaliRepo = $scaffaliRepo;
    }
    public function execute(int $id): array {
        return $this->scaffaliRepo->getScaffaliArmadioById($id);
    }
}