<?php
declare(strict_types=1);
namespace Backend\Application\mappers;
use Backend\Application\dtos\ReadSchoolClassDTO;
use Backend\Application\dtos\ReadUserDTO;
use Backend\Application\dtos\ReadUserPwdDTO;
use Backend\Domain\SchoolClass;
use Backend\Domain\User;
use Backend\Domain\UserPwd;
class ReadMapper
{
    //TODO
    public static function ReadUserDTO_To_User(ReadUserDTO $dto): User
    {
        return new User(
            $dto->nome,
            $dto->cognome,
            $dto->email,
            $dto->materia
        );
    }
    public static function ReadSchoolClassDTO_To_SchoolClass(ReadSchoolClassDTO $dto): SchoolClass
    {
        return new SchoolClass(
            $dto->id,
            $dto->nome,
            $dto->link,
            $dto->materia,
            $dto->respoEmail
        );
    }
    public static function ReadUserPwdDTO_To_UserPwd(ReadUserPwdDTO $dto): UserPwd
    {
        return new UserPwd(
            $dto->password,
            $dto->email
        );
    }
}
