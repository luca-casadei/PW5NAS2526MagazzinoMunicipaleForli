<?php
declare(strict_types=1);
namespace Backend\Presentation\Controllers;
use Backend\Application\interfaces\serv\ISchoolClassService;
use Backend\Application\interfaces\serv\IUserService;
use Backend\Application\interfaces\ISession;
use Backend\Application\commands\CreateSchoolClassDTO;
use Backend\Presentation\Response;
use Backend\Presentation\mapper\PresentationMapper;

class SchoolClassController {
    private ISchoolClassService $classService;
    private IUserService $userService;
    private ISession $sessionManager;

    public function __construct(ISchoolClassService $classService, IUserService $userService, ISession $sessionManager) {
        $this->classService = $classService;
        $this->userService = $userService;
        $this->sessionManager = $sessionManager;
    }
    public function enterClass(){
        if (!$this->sessionManager->is_logged_in()) {
            $resp = new Response("error","Devi fare il login per vedere le tue classi", 401);
            $this->json_response($resp, $resp->get_code());
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $currentUserEmail = $this->sessionManager->get_current_user();
        $link = $input['link'] ?? '';
        if(empty($link)){
            $resp = new Response("error","Link mancante", 400);
            $this->json_response($resp, $resp->get_code());
            return;
        }
        
        try{
            $this->classService->enterClass($link, $currentUserEmail);
            $resp = new Response("success", "Sei entrato nella classe", 200);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function getClassesAsRespo(){
        if (!$this->sessionManager->is_logged_in()) {
            $resp = new Response("error","Devi fare il login per vedere le tue classi", 401);
            $this->json_response($resp, $resp->get_code());
            return;
        }
        $currentUserEmail = $this->sessionManager->get_current_user();
        
        try{
            $userRespo = $this->userService->getUserByEmail($currentUserEmail);
            $classes = $this->classService->getClassesOfRespo($currentUserEmail);
            $classesResp = [];
            foreach($classes as $class){
                $classesResp[] = PresentationMapper::schoolClass_to_ResponseSchoolClass($class, $userRespo);
            }
            $resp = new Response("success", "Tue classi da responsabile recuperate correttamente!", 200, $classesResp);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function getClassesOfARespo(){
        if (!$this->sessionManager->is_logged_in()) {
            $resp = new Response("error","Devi fare il login per vedere le tue classi", 401);
            $this->json_response($resp, $resp->get_code());
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $respoEmail = $input['respoEmail'] ?? '';
        
        try{
            $userRespo = $this->userService->getUserByEmail($respoEmail);
            $classes = $this->classService->getClassesOfRespo($respoEmail);
            $classesResp = [];
            foreach($classes as $class){
                $classesResp[] = PresentationMapper::schoolClass_to_ResponseSchoolClass($class, $userRespo);
            }
            $resp = new Response("success", "Classi del responsabile recuperate correttamente!", 200, $classesResp);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function getUserClasses(){
        if (!$this->sessionManager->is_logged_in()) {
            $resp = new Response("error","Devi fare il login per vedere le tue classi", 401);
            $this->json_response($resp, $resp->get_code());
            return;
        }

        $currentUserEmail = $this->sessionManager->get_current_user();
        
        try{
            $classes = $this->classService->getUserClasses($currentUserEmail);
            $classesUser = [];
            foreach($classes as $class){
                $userRespo = $this->userService->getUserByEmail($class->get_respo());
                $classesUser[] = PresentationMapper::schoolClass_to_ResponseSchoolClass($class, $userRespo);
            }
            $resp = new Response("success", "Classi utente recuperate correttamente!", 200, $classesUser);
            $this->json_response($resp, $resp->get_code());
        }
        catch(\Exception $e){
            $resp = new Response("error", $e->getMessage(), $e->getCode() ?: 500);
            $this->json_response($resp, $resp->get_code());
             return;
        }
    }
    public function create_class() {
        //Se non sei loggato, ti blocca
        if (!$this->sessionManager->is_logged_in()) {
            $resp = new Response("error","Devi fare il login per creare una classe", 401);
            $this->json_response($resp, $resp->get_code());
            return; 
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        // Se sei arrivato fin qui, significa che sei loggato.
        $currentUserEmail = $this->sessionManager->get_current_user();
        
        // Creiamo il DTO senza il link (lo genera il Service)
        $createClass = new CreateSchoolClassDTO($input['nome'], $input['materia'], $currentUserEmail);
        
        try {
            // chiamata al service
            $link = $this->classService->createClass($createClass);
            
            $resp = new Response("success","Classe creata correttamente! Link: " . $link, 201);
            $this->json_response($resp, $resp->get_code());

        } catch (\Exception $e) {
            // Se il Service fallisce, catturiamo l'errore qui
            $resp = new Response("error", $e->getMessage(), 500);
            $this->json_response($resp, $resp->get_code());
            return;
        }
    }
    public function method_not_allowed(){
        $resp = new Response("error", "Metodo non consentito", 405);
        $this->json_response($resp, $resp->get_code());
    }

    private function json_response(Response $resp, int $status = 200): void
    {
        header("Content-Type: application/json", true, $status);
        echo json_encode($resp);
        exit();
    }
}

