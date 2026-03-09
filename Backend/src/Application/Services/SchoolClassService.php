<?php
declare(strict_types=1);
namespace Backend\Application\Services;
use Backend\Application\interfaces\serv\ISchoolClassService;
use Backend\Application\interfaces\repo\ISchoolClassRepository;
use Backend\Application\commands\CreateSchoolClassDTO;
use Backend\Application\mappers\CreateMapper;
use Backend\Domain\SchoolClass;

class SchoolClassService implements ISchoolClassService{
    private ISchoolClassRepository $repo;
    public function __construct(ISchoolClassRepository $repository){
        $this->repo = $repository;
    }
    public function enterClass(string $link, string $emailUser):void{
        $class = $this->repo->getClassByLink($link);
        if($class === null){
            throw new \Exception("Link non valido", 400);
        }
        // Controlliamo se l'utente è già iscritto alla classe
        $isAlreadyInClass = $this->repo->isUserInClass($emailUser, $class->get_id());
        if($isAlreadyInClass){
            throw new \Exception("Sei già iscritto a questa classe", 400);
        }
        $this->repo->addUserToClass($emailUser, $class->get_id());
    }
    public function getClassById(int $id):?SchoolClass{
        return $this->repo->getClassById($id);
    }
    public function getClassesOfRespo(string $emailRespo):array {
        return$this->repo->getClassesOfRespo($emailRespo);
    }
    public function getUserClasses(string $emailUser):array{
        return $this->repo->getUserClasses($emailUser);
    }
    public function createClass(CreateSchoolClassDTO $classCreate):string{
        $link = "";
        $is_unique = false;
        $max_tentativi = 5; 
        $tentativi_fatti = 0;
        
        do {
            $link = bin2hex(random_bytes(16));
            // Qui il Service chiama il suo stesso Repository (o un altro metodo)
            $esiste_gia = $this->repo->is_link_exists($link);
            
            if (!$esiste_gia) {
                $is_unique = true;
            }
            $tentativi_fatti++;
        } while (!$is_unique && $tentativi_fatti < $max_tentativi);

        if (!$is_unique) {
            // Lanciamo l'eccezione, che verrà catturata dal blocco try-catch nel Controller
            throw new \Exception("Errore di sistema: impossibile generare un link per la classe. Riprova.", 500);
        }
        $class = CreateMapper::CreateSchoolClassDTO_To_SchoolClass($classCreate, $link);
        $this->repo->createClass($class);
        return $link;
    }
}
