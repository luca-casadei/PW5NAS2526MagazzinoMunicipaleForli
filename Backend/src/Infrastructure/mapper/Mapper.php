<?php
declare(strict_types=1);
namespace Backend\Infrastructure\mapper;
use Backend\Infrastructure\dtos\UserDTO;
use Backend\Infrastructure\dtos\UserPwdDTO;
use Backend\Infrastructure\dtos\SchoolClassDTO;
use Backend\Domain\SchoolClass;
use Backend\Domain\User;
use Backend\Domain\UserPwd;
class Mapper{
    //TODO
    public static function User_To_DTO(User $user):UserDTO
    {
        return new UserDTO(
            $user->get_name(),
            $user->get_surname(),
            $user->get_email(),
            $user->get_subject()
        );
    }
    public static function UserPwd_To_DTO(UserPwd $userPwd):UserPwdDTO
    {
        return new UserPwdDTO(
            $userPwd->get_email(),
            $userPwd->get_password()
        );
    }

    public static function SchoolClass_To_DTO(SchoolClass $schoolClass):SchoolClassDTO{
        return new SchoolClassDTO(
            $schoolClass->get_id(),
            $schoolClass->get_name(),
            $schoolClass->get_link(),
            $schoolClass->get_materia(),
            $schoolClass->get_respo()
        );
    }

    public static function DTO_To_User(UserDTO $userDTO):User{
        return new User(
            $userDTO->nome,
            $userDTO->cognome,
            $userDTO->email,
            $userDTO->materia
        );
    }
    public static function DTO_To_UserPwd(UserPwdDTO $userPwdDTO):UserPwd{
        return new UserPwd(
            $userPwdDTO->email,
            $userPwdDTO->password
        );
    }
    
    public static function DTO_To_SchoolClass(SchoolClassDTO $schoolClassDTO):SchoolClass
    {
        return new SchoolClass(
            $schoolClassDTO->id,
            $schoolClassDTO->name,
            $schoolClassDTO->link,
            $schoolClassDTO->materia,
            $schoolClassDTO->respoEmail
        );
    }
}