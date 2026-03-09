<?php
declare(strict_types=1);
namespace Backend\Domain;
use InvalidArgumentException;
class SchoolClass {
    private int $id;
    private string $name;
    private string $link;
    private string $materia;
    private string $respoEmail; 

    public function __construct(int $id, string $name, string $link, string $materia, string $respoEmail) {
        $this->set_id($id);
        $this->respoEmail = $respoEmail;
        $this->set_name($name);
        $this->set_link($link);
        $this->set_materia($materia);
    }
    public function get_id(): int { return $this->id; }
    public function get_name(): string { return $this->name; }
    public function get_link(): string { return $this->link; }
    public function get_materia(): string { return $this->materia; }
    public function get_respo(): string { return $this->respoEmail; }

    public function set_id(int $id): void {
        if ($id < 0) throw new InvalidArgumentException("ID deve essere un intero positivo");
        $this->id = $id;
    }
    public function set_name(string $name): void {
        if (empty($name)) throw new InvalidArgumentException("Il nome della classe non può essere vuoto");
        if (strlen($name) > 30) throw new InvalidArgumentException("Il nome della classe è troppo lungo");
        $this->name = trim($name);
    }

    public function set_link(string $link): void {
        if (empty($link)) throw new InvalidArgumentException("Il link non può essere vuoto");
        // Validazione base URL
        if (strlen($link) > 255) throw new InvalidArgumentException("Il link è troppo lungo");
        $this->link = trim($link);
    }

    public function set_materia(string $materia): void {
        if (empty($materia)) throw new InvalidArgumentException("La materia non può essere vuota");
        if (strlen($materia) > 20) throw new InvalidArgumentException("La materia è troppo lunga");
        $this->materia = trim($materia);
    }
}