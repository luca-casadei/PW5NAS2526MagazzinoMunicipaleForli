<?php 
declare(strict_types=1);
namespace Backend;
    // Dico a PHP di registrare questa funzione anonima come "autoloader".
    // Verrà eseguita automaticamente ogni volta che il codice cerca una classe che non è stata ancora caricata.
    spl_autoload_register(function (string $class_name) {
        // Definisco il prefisso del namespace di cui mi voglio occupare.
        $prefix = 'Backend\\';
        // Imposto la cartella radice dove cercare i file, usando la directory corrente (__DIR__).
        $base_dir = __DIR__ . '/';

        // Controllo se la classe che PHP sta cercando inizia con il prefisso 'Backend\'.
        if (!str_starts_with($class_name, $prefix)) {
            // Se la classe non fa parte del namespace 'Backend', non è compito mio caricarla, quindi esco.
            return;
        }
        // Tolgo il prefisso dal nome della classe per ottenere solo la parte relativa
        $relative_class = substr($class_name, strlen($prefix));

        // Costruisco il percorso finale del file: unisco la cartella base, sostituisco i 
        // backslash del namespace con gli slash delle cartelle perché namespace usa 
        // \\ mentre filesystem / e aggiungo .php.
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // Verifico fisicamente se il file esiste nel percorso che ho calcolato.
        if (file_exists($file)) {
            // Se il file esiste, lo includo nello script così la classe diventa utilizzabile.
            require $file;
        }
    });
?>