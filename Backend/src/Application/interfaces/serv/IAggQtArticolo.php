<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IAggQtArticolo{
    public function enterClass(string $link, string $emailUser):void;
    public function getClassById(int $id):?SchoolClass;
    public function getClassesOfRespo(string $emailRespo):array;
    public function getUserClasses(string $emailUser):array;
    public function createClass(CreateSchoolClassDTO $classCreate):string;
}