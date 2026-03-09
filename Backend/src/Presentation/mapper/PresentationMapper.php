<?php
declare(strict_types=1);
namespace Backend\Presentation\mapper;
use Backend\Presentation\dtos\ResponseSchoolClass;
use Backend\Domain\SchoolClass;
use Backend\Domain\User;
class PresentationMapper {
    //TODO
    public static function schoolClass_to_ResponseSchoolClass(SchoolClass $schoolClass, User $respo): ResponseSchoolClass {
        return new ResponseSchoolClass(
            $schoolClass->get_name(),
            $schoolClass->get_link(),
            $schoolClass->get_materia(),
            $schoolClass->get_respo(),
            $respo->get_name() . " " . $respo->get_surname()
        );  
    }
}