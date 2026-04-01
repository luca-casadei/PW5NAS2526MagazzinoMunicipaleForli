<?php
declare(strict_types=1);
namespace Backend\Infrastructure\Repositories;
use Backend\Infrastructure\DatabaseConnector;
use Backend\Infrastructure\dtos\ArticoloDTO;
use Backend\Infrastructure\mapper\Mapper;
use Backend\Infrastructure\dtos\SchoolClassDTO;
use Backend\Domain\SchoolClass;
use Backend\Application\interfaces\repo\ISchoolClassRepository;

class SchoolClassRepository implements ISchoolClassRepository{
    private DatabaseConnector $connector;
    public function __construct(DatabaseConnector $connector){
        $this->connector = $connector;
    }
    public function getClassById(int $id):?SchoolClass{
        $db = $this->connector->get_db();
        $query = "SELECT C.nome, C.link, C.materia, U.email 
        FROM Classi AS C JOIN Utenti AS U ON C.Id_Utente_Respo = U.Utente_Id 
        WHERE C.Classe_Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if (!$res) {
            return null;
        }
        $dto = new SchoolClassDTO($id,$res["nome"], $res["link"], $res["materia"], $res["email"]);
        return Mapper::DTO_To_SchoolClass($dto);
    }
    public function getClassByLink(string $link):?SchoolClass{
        $db = $this->connector->get_db();
        $query = "SELECT C.Classe_Id, C.nome, C.link, C.materia, U.email FROM Classi AS C JOIN Utenti AS U ON C.Id_Utente_Respo = U.Utente_Id WHERE link = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $link);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if (!$res) {
            return null;
        }
        $dto = new SchoolClassDTO($res["Classe_Id"],$res["nome"], $res["link"], $res["materia"], $res["email"]);
        return Mapper::DTO_To_SchoolClass($dto);
    }
    public function getClassesOfRespo(string $emailRespo):array{
        $db = $this->connector->get_db();
        $query = "SELECT Classe_Id, nome, link, materia FROM Classi WHERE Id_Utente_Respo = (SELECT Utente_Id FROM Utenti WHERE email = ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $emailRespo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $classes = [];
        foreach($result as $res){
            $dto = new SchoolClassDTO($res["Classe_Id"],$res["nome"], $res["link"], $res["materia"], $emailRespo);
            $classes[] = Mapper::DTO_To_SchoolClass($dto);
        }
        return $classes;
    }
    public function getUserClasses(string $emailUser): array {
        $db = $this->connector->get_db();
        $query = "SELECT C.Classe_Id, C.nome, C.link, C.materia, P.email AS email 
                FROM Classi AS C 
                JOIN Frequenta AS F ON F.Id_Classe = C.Classe_Id 
                JOIN Utenti AS U ON F.Id_Utente = U.Utente_Id 
                JOIN Utenti AS P ON C.Id_Utente_Respo = P.Utente_Id 
                WHERE U.email = ?";
        $stmt = $db->prepare($query);
            $stmt->bind_param("s", $emailUser);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $classes = [];
            foreach($result as $res){
                $dto = new SchoolClassDTO($res["Classe_Id"],$res["nome"], $res["link"], $res["materia"], $res["email"]);
                $classes[] = Mapper::DTO_To_SchoolClass($dto);
            }
        
        return $classes;
    }
    public function createClass(SchoolClass $class):void{
        $db = $this->connector->get_db();
        $query = "INSERT INTO Classi (nome, link, materia, Id_Utente_Respo) VALUES (?, ?, ?, (SELECT Utente_Id FROM Utenti WHERE email = ?))";
        $stmt = $db->prepare($query);
        $name = $class->get_name();
        $link = $class->get_link();
        $materia = $class->get_materia();
        $emailRespo = $class->get_respo();
        $stmt->bind_param("ssss", $name, $link, $materia, $emailRespo);
        $stmt->execute();
    }
    public function addUserToClass(string $userEmail, int $classId): void {
        $db = $this->connector->get_db();
        $query = "INSERT INTO Frequenta (Id_Utente, Id_Classe) VALUES ((SELECT Utente_Id FROM Utenti WHERE email = ?), ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $userEmail, $classId);
        $stmt->execute();
    }
    public function is_link_exists(string $link): bool {
        $db = $this->connector->get_db();
        // Contiamo quante righe hanno questo esatto link
        $query = "SELECT COUNT(*) as conteggio FROM Classi WHERE link = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $link);
        $stmt->execute();
        
        $res = $stmt->get_result()->fetch_assoc();
        return $res['conteggio'] > 0;
    }
    public function isUserInClass(string $userEmail, int $classId): bool {
        $db = $this->connector->get_db();
        // Contiamo quante righe hanno questo esatto link
        $query = "SELECT COUNT(*) as conteggio FROM Frequenta WHERE Id_Utente = (SELECT Utente_Id FROM Utenti WHERE email = ?) AND Id_Classe = ?";
        $result = $db->query($query);
        $res = $result->fetch_all(MYSQLI_ASSOC);
        $articoli = [];
        foreach ($res as $elem) {
            $dto = new ArticoloDTO($elem['Articolo_Id'], $elem['Tipologia_Id']);
            array_push($articoli, Mapper::DTO_To_Articolo($dto));
        }
        return $articoli;

    }
}