<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\repo;
use Backend\Domain\SchoolClass;
interface ISchoolClassRepository{
    public function getClassById(int $id):?SchoolClass;
    public function getClassByLink(string $link):?SchoolClass;
    public function getClassesOfRespo(string $emailRespo):array;
    public function getUserClasses(string $emailUser):array;
    public function createClass(SchoolClass $class):void;
    public function addUserToClass(string $userEmail, int $classId): void;
    public function is_link_exists(string $link): bool;
    public function isUserInClass(string $userEmail, int $classId): bool;
}