<?php
declare(strict_types=1);
namespace Backend\Application\mappers;
use Backend\Application\commands\CreateSchoolClassDTO;
use Backend\Application\commands\CreateUserDTO;
use Backend\Application\commands\CreateUserPwdDTO;
use Backend\Domain\SchoolClass;
use Backend\Domain\User;
use Backend\Domain\UserPwd;

class CreateMapper
{
    //TODO
    public static function CreateSchoolClassDTO_To_SchoolClass(CreateSchoolClassDTO $dto, string $link): SchoolClass
    {
        return new SchoolClass(
            0,
            $dto->nome,
            $link,
            $dto->materia,
            $dto->respoEmail
        );
    }
    public static function CreateUserDTO_To_User(CreateUserDTO $dto): User
    {
        return new User(
            $dto->nome,
            $dto->cognome,
            $dto->email,
            $dto->materia,
        );
    }
    public static function CreateUserPwdDTO_To_UserPwd(CreateUserPwdDTO $dto): UserPwd
    {
        return new UserPwd(
            $dto->email,
         $dto->pwd
        );
    }
}
