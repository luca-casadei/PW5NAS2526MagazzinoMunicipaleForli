<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;

use Backend\Application\commands\CreateAttrArtDTO;

interface IAssociaAttributoRepo {
    public function associaAttributo(CreateAttrArtDTO $dto): void;
}