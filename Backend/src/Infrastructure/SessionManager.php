<?php
/*
declare(strict_types=1);
namespace Backend\Infrastructure;
use Backend\Application\interfaces\ISession;

class SessionManager implements ISession {
    //TODO
    
    public function __construct() {
        // Avvia la sessione solo se non è già stata avviata
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login_user(string $email): void {
        $_SESSION['email'] = $email;
    }

    public function logout_user(): void {
        unset($_SESSION['email']);
        session_destroy();
    }

    public function is_logged_in(): bool {
        return isset($_SESSION['email']);
    }

    public function get_current_user(): ?string {
        if ($this->is_logged_in()) {
            return $_SESSION['email'];
        }
        return null;
    }
        
}
    */