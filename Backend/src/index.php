<?php
declare(strict_types=1);
namespace Backend;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Backend\config\IniParser;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\SessionManager;
use Backend\Infrastructure\Repositories\SchoolClassRepository;
use Backend\Infrastructure\Repositories\UserPwdRepository;
use Backend\Infrastructure\Repositories\UserRepository;
use Backend\Application\Services\SchoolClassService;
use Backend\Application\Services\UserService;
use Backend\Application\Services\UserPwdService;
use Backend\Presentation\Controllers\AuthController;
use Backend\Presentation\Controllers\SchoolClassController;

try{
    if (!file_exists(__DIR__ . "/autoload.php")) {
        throw new \Exception("Autoloader non trovato in: " . __DIR__ . "/autoload.php");
    }
    require __DIR__ . "/autoload.php";
}
catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Errore durante il caricamento dell'autoloader", "detail" => $e->getMessage()]);
    exit;
}

try{
    // nuova sessione
    $sessionManager = new SessionManager();
    //caricamento config per database
    $iniconfig = new IniParser();
    $config_dto = $iniconfig->load_maria_config("config.ini");

    $connection = DatabaseConnector::get_instance($config_dto);
    
    $schoolClass_repository = new SchoolClassRepository($connection);

    $schoolClass_service = new SchoolClassService($schoolClass_repository);

    $class_Controller = new SchoolClassController($schoolClass_service, $user_service,$sessionManager);

    //prende solo url ed elimina anche parametri
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $method = $_SERVER["REQUEST_METHOD"];
}catch(\Exception $e){
    http_response_code(500);
    echo json_encode(["error" => "Errore altro", "detail" => $e->getMessage()]);
    exit;
}

try{
    switch ($path) {
        // --- ROTTE AUTENTICAZIONE ---
        case '/login':
            if ($method === 'POST') {
                $auth_controller->login();
            }
            else{
                $auth_controller->method_not_allowed();
            }
            break;

        case '/logout':
            if ($method === 'DELETE') {
                $auth_controller->logout();
            }
            else{
                $auth_controller->method_not_allowed();
            }
            break;
        
        case '/register':
            if ($method === 'POST') {
                $auth_controller->register();
            }
            else{
                $auth_controller->method_not_allowed();
            }
            break;

        case '/enterClass':
            if ($method === 'POST') {
                $class_Controller->enterClass();
            }
            else{
                $class_Controller->method_not_allowed();
            }
            break;
        
        case '/createClass':
            if ($method === 'POST') {
                $class_Controller->create_class();
            }
            else{
                $class_Controller->method_not_allowed();
            }
            break;
        
        case '/myClasses':
            if ($method === 'GET') {
                $class_Controller->getUserClasses();
            }
            else{
                $class_Controller->method_not_allowed();
            }
            break;
        
        case '/respoClasses':
            if ($method === 'POST') {
                $class_Controller->getClassesOfARespo();
            }
            else{
                $class_Controller->method_not_allowed();
            }
            break;
        case '/classesAsRespo':
            if ($method === 'GET') {
                $class_Controller->getClassesAsRespo();
            }
            else{
                $class_Controller->method_not_allowed();
            }
            break;
        // --- SE NESSUNA ROTTA CORRISPONDE ---
        default:
            http_response_code(404);
            echo json_encode(["error" => "Endpoint non trovato", "path" => $path]);
            break;
    }
}
catch (\Exception $e) {
    // Cattura globale degli errori non gestiti
    http_response_code(500);
    echo json_encode(["error" => "Errore interno del server", "detail" => $e->getMessage()]);
}
?>