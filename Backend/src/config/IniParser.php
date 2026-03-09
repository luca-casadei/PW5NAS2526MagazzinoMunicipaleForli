<?php
namespace Backend\config;

use Backend\config\dtos\MariaDTO;

class IniParser {
    public function load_maria_config(string $filename): MariaDTO{
        // Leggo il file .ini e lo converto in un array associativo.
        $file_ini = parse_ini_file($filename);
        return new MariaDTO(
            $file_ini["db_hostname"],
            $file_ini["db_password"],
            $file_ini["db_user"],
            $file_ini["db_name"],
            $file_ini["db_port"]
        );
    }
}
