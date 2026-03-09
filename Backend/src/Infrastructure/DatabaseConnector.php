<?php
declare(strict_types=1);
namespace Backend\Infrastructure;

use Backend\config\dtos\MariaDTO;
use \mysqli;
class DatabaseConnector
{
    private static ?DatabaseConnector $instance = null;
    private mysqli $dbconn;

    private function __construct(MariaDTO $mariaDTO)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->dbconn = new mysqli($mariaDTO->host, $mariaDTO->user, $mariaDTO->password, $mariaDTO->dbname, $mariaDTO->port);

        if ($this->dbconn->connect_error) {
            die("Connessione fallita:" . $this->dbconn->connect_error);
        }
        $this->dbconn->options(MYSQLI_REPORT_STRICT, true);
    }

    public static function get_instance(MariaDTO $mariaDTO): DatabaseConnector
    {
        if (DatabaseConnector::$instance === null) {
            DatabaseConnector::$instance = new DatabaseConnector($mariaDTO);
        }
        return DatabaseConnector::$instance;
    }
    
    public function get_db(): mysqli
    {
        return $this->dbconn;
    }
}
