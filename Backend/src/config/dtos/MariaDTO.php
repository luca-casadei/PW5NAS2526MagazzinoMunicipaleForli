<?php

namespace Backend\config\dtos;
//use SensitiveParameter;
readonly class MariaDTO
{
    public string $host;
    //#[SensitiveParameter] // Nasconde la password nei log di errore/stack trace, non so dove va
    public string $password;
    public string $user;
    public string $dbname;
    public int $port;
    public function __construct(string $host,string $password,string $user,string $dbname,int $port)
    {
        $this->host = $host;
        $this->password = $password;
        $this->user = $user;
        $this->dbname = $dbname;
        $this->port = $port;
    }
}
