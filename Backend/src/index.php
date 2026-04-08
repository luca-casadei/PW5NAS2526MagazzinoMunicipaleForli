<?php
declare(strict_types=1);
namespace Backend;

use Backend\Application\Services\anagrafiche\CreaTipoArticolo;
use Backend\Application\Services\anagrafiche\CreaTipologia;
use Backend\Application\Services\anagrafiche\EliminaTipoArticolo;
use Backend\Application\Services\anagrafiche\EliminaTipologia;
use Backend\Application\Services\magazzino\AggQtArticolo;
use Backend\Application\Services\magazzino\CreaArmadio;
use Backend\Application\Services\magazzino\CreaScaffale;
use Backend\Application\Services\magazzino\DimQtArticolo;
use Backend\Application\Services\magazzino\MostraTuttiArticoli;
use Backend\Application\Services\magazzino\MostraTuttiArticoliQtBasse;
use Backend\Application\Services\magazzino\VediTipologie;
use Backend\Application\Services\ricerca\EsportaStorico;
use Backend\Application\Services\ricerca\GetAllScaffali;
use Backend\Application\Services\ricerca\GetScaffaliArmadioById;
use Backend\Application\Services\ricerca\GetTipologiaById;
use Backend\Application\Services\ricerca\LocalizzaArticolo;
use Backend\Application\Services\ricerca\TrovaArticoliByName;
use Backend\Application\Services\ricerca\VediArmadi;
use Backend\Application\Services\ricerca\VediContenutoScaffali;
use Backend\Infrastructure\Repositories\anagrafiche\CreaTipologiaRepo;
use Backend\Infrastructure\Repositories\anagrafiche\EliminaTipologiaRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\AssociaAttributoRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\CreaArticoloRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\CreaAttributoRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\EliminaArticoloRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\GetArticoloPerFirmaRepo;
use Backend\Infrastructure\Repositories\anagrafiche\gestioneTipoArticolo\GetAttributoByIdRepo;
use Backend\Infrastructure\Repositories\magazzino\AggQtArticoloRepo;
use Backend\Infrastructure\Repositories\magazzino\CreaArmadioRepo;
use Backend\Infrastructure\Repositories\magazzino\CreaScaffaleRepo;
use Backend\Infrastructure\Repositories\magazzino\GetAttributiByArtId;
use Backend\Infrastructure\Repositories\magazzino\GetQtArticoloRepo;
use Backend\Infrastructure\Repositories\magazzino\GetQtTotArtRepo;
use Backend\Infrastructure\Repositories\magazzino\GetValoriAttributi;
use Backend\Infrastructure\Repositories\magazzino\TrovaArticoloConIdRepo;
use Backend\Infrastructure\Repositories\magazzino\VediArticoliByNomeRepo;
use Backend\Infrastructure\Repositories\ricerca\VediArticoliPerScaffaleRepo;
use Backend\Infrastructure\Repositories\magazzino\VediArticoliRepo;
use Backend\Infrastructure\Repositories\magazzino\VediTipologieRepo;
use Backend\Infrastructure\Repositories\magazzino\VediTutteTipologieRepo;
use Backend\Infrastructure\Repositories\ricerca\EsportaStoricoRepo;
use Backend\Infrastructure\Repositories\ricerca\GetTipologiaByIdRepo;
use Backend\Infrastructure\Repositories\ricerca\LocalizzaArticoloRepo;
use Backend\Infrastructure\Repositories\ricerca\VediArmadiRepo;
use Backend\Infrastructure\Repositories\ricerca\VediScaffaliRepo;
use Backend\Presentation\Controllers\ArmadiController;
use Backend\Presentation\Controllers\ArticoliQuantitaController;
use Backend\Presentation\Controllers\EsportaStoricoCsvController;
use Backend\Presentation\Controllers\ListaArticoliController;
use Backend\Presentation\Controllers\ListaArticoliPerController;
use Backend\Presentation\Controllers\LocalizzaArticoloController;
use Backend\Presentation\Controllers\ScaffaliController;
use Backend\Presentation\Controllers\TipoArticoloController;
use Backend\Presentation\Controllers\TipologieController;
use Backend\config\IniParser;
use Backend\Infrastructure\DatabaseConnector;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


try{
    if (!file_exists(__DIR__ . "/autoload.php")) {
        throw new \Exception("Autoloader non trovato in: " . __DIR__ . "/autoload.php");
    }
    require __DIR__ . "/autoload.php";
}
catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Errore durante il caricamento dell'autoloader", "detail" => $e->getMessage()]);
    exit;
}

try{
    //database connector
    $iniconfig = new IniParser();
    $config_dto = $iniconfig->load_maria_config("config.ini");
    $connection = DatabaseConnector::get_instance($config_dto);

    //repositories
    $creaTipologiaRepo = new CreaTipologiaRepo($connection);
    $eliminaTipologiaRepo = new EliminaTipologiaRepo($connection);
    $associaAttrRepo = new AssociaAttributoRepo($connection);
    $creaArtRepo = new CreaArticoloRepo($connection);
    $creaAttrRepo = new CreaAttributoRepo($connection);
    $eliminaArtRepo = new EliminaArticoloRepo($connection);
    $getArtPerFirmaRepo = new GetArticoloPerFirmaRepo($connection);
    $getAttrByIdRepo = new GetAttributoByIdRepo($connection);
    $aggiornaQtArtRepo = new AggQtArticoloRepo($connection);
    $creaArmadioRepo = new CreaArmadioRepo($connection);
    $creaScaffaleRepo = new CreaScaffaleRepo($connection);
    $getAttrByArtIdRepo = new GetAttributiByArtId($connection);
    $getQtArtRepo = new GetQtArticoloRepo($connection);
    $getQtTotRepo = new GetQtTotArtRepo($connection);
    $getValoriAttrRepo = new GetValoriAttributi($connection);
    //$trovaArtConIdRepo = new TrovaArticoloConIdRepo($connection);
    $vediArtByNomeRepo = new VediArticoliByNomeRepo($connection);
    $vediArtRepo = new VediArticoliRepo($connection);
    $vediTipologieRepo = new VediTipologieRepo($connection);
    $vediTutteTipologieRepo = new VediTutteTipologieRepo($connection);
    $esportaStoricoRepo = new EsportaStoricoRepo($connection);
    $getTipologiaByIdRepo = new GetTipologiaByIdRepo($connection);
    $localizzaArtRepo = new LocalizzaArticoloRepo($connection);
    $vediArmadiRepo = new VediArmadiRepo($connection);
    $vediScaffaliRepo = new VediScaffaliRepo($connection);
    $vediArtPerScaffaleRepo = new VediArticoliPerScaffaleRepo($connection);
    
    //services
    $creaTipoArtService = new CreaTipoArticolo(
        $associaAttrRepo,
        $creaArtRepo,
        $creaAttrRepo,
        $getArtPerFirmaRepo,
        $getAttrByIdRepo
    );
    $creaTipologiaService = new CreaTipologia($creaTipologiaRepo);
    $eliminaTipoArtService = new EliminaTipoArticolo($eliminaArtRepo);
    $eliminaTipologiaService = new EliminaTipologia($eliminaTipologiaRepo);
    $aggQtArtService = new AggQtArticolo($aggiornaQtArtRepo,$getQtArtRepo);
    $dimQtArtService = new DimQtArticolo($aggiornaQtArtRepo,$getQtArtRepo);
    $creaArmadioService = new CreaArmadio($creaArmadioRepo);
    $creaScaffaleService = new CreaScaffale($creaScaffaleRepo);
    $vediArticoliService = new MostraTuttiArticoli(
        $vediArtRepo,
        $vediTipologieRepo,
        $getAttrByArtIdRepo,
        $getValoriAttrRepo,
        $getQtTotRepo
    );
    $vediArtQtBasseService = new MostraTuttiArticoliQtBasse(
        $vediArtRepo,
        $vediTipologieRepo,
        $getAttrByArtIdRepo,
        $getValoriAttrRepo,
        $getQtTotRepo
    );
    $vediTipologieService = new VediTipologie($vediTutteTipologieRepo);
    $esportaStoricoService = new EsportaStorico($esportaStoricoRepo);
    $getAllScaffaliService = new GetAllScaffali($vediArmadiRepo, $vediScaffaliRepo);
    $getScaffaliArmadioByIdService = new GetScaffaliArmadioById($vediScaffaliRepo);
    $getTipologiaByIdService = new GetTipologiaById($getTipologiaByIdRepo);
    $localizzaArtService = new LocalizzaArticolo($localizzaArtRepo);
    $trovaArtByNomeService = new TrovaArticoliByName(
        $vediArtByNomeRepo,
        $vediTipologieRepo,
        $getAttrByArtIdRepo,
        $getValoriAttrRepo,
        $getQtTotRepo
    );
    $vediArmadiService = new VediArmadi($vediArmadiRepo);
    $vediScaffaliService = new VediContenutoScaffali(
        $vediArtPerScaffaleRepo,
        $vediTipologieRepo,
        $getAttrByArtIdRepo,
        $getValoriAttrRepo,
        $getQtArtRepo
    );
    
    //controllers
    $armadiController = new ArmadiController($creaArmadioService, $vediArmadiService);
    $articoliPerController = new ListaArticoliPerController(
        $trovaArtByNomeService,
        $vediScaffaliService,
        $getTipologiaByIdService
    );
    $articoliQuantitaController = new ArticoliQuantitaController($aggQtArtService, $dimQtArtService);
    $esportaStoricoCSVController = new EsportaStoricoCsvController($esportaStoricoService);
    $listaArticoliController = new ListaArticoliController(
        $vediArticoliService,
        $getTipologiaByIdService,
        $vediArtQtBasseService
    );
    $localizzaArticoloController = new LocalizzaArticoloController($localizzaArtService);
    $scaffaliController = new ScaffaliController(
        $creaScaffaleService,
        $getAllScaffaliService,
        $getScaffaliArmadioByIdService
    );
    $tipoArticoloController = new TipoArticoloController($creaTipoArtService, $eliminaTipoArtService);
    $tipologieController = new TipologieController(
        $creaTipologiaService,
        $eliminaTipologiaService,
        $vediTipologieService
    );

    //prende solo url ed elimina anche parametri
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $method = $_SERVER["REQUEST_METHOD"];
}
catch (\Throwable $e){
    http_response_code(500);
    echo json_encode([
            "error" => "Errore files", 
            "detail" => $e->getMessage(),
            "file" => $e->getFile(),     // <--- AGGIUNGI QUESTO
            "line" => $e->getLine()      // <--- E QUESTO
        ]);
    exit;
}

try{
    switch ($path) {
        // --- ROTTE AUTENTICAZIONE ---
        case '/armadi':
            if ($method === 'POST') {
                $armadiController->crea_armadio();
            }
            else if ($method === 'GET') {
                $armadiController->get_armadi();
            }
            else{
                $armadiController->method_not_allowed();
            }
            break;

        case '/scaffali':
            if ($method === 'POST') {
                $scaffaliController->crea_scaffale();
            }
            else if ($method === 'GET') {
                $scaffaliController->get_all();
            }
            else{
                $scaffaliController->method_not_allowed();
            }
            break;
        case '/armadi/scaffali':
            if ($method === 'POST') {
                $scaffaliController->get_scaffali_of_armadio();
            }
            else{
                $scaffaliController->method_not_allowed();
            }
            break;
        case '/tipologie':
            if ($method === 'POST') {
                $tipologieController->crea_tipologia();
            }
            else if ($method === 'GET') {
                $tipologieController->get_tipologie();
            }
            else if ($method === 'DELETE') {
                $tipologieController->elimina_tipologia();
            }
            else{
                $tipologieController->method_not_allowed();
            }
            break;
        case '/tipiArticoli':
            if ($method === 'POST') {
                $tipoArticoloController->crea_tipoArticolo();
            }
            else if ($method === 'DELETE') {
                $tipoArticoloController->elimina_tipoArticolo();
            }
            else{
                $tipoArticoloController->method_not_allowed();
            }
            break;
        case '/localizzaArticolo':
            if ($method === 'POST') {
                $localizzaArticoloController->localizza();
            }
            else{
                $localizzaArticoloController->method_not_allowed();
            }
            break;
        case '/articoli':
            if ($method === 'GET') {
                $listaArticoliController->get_articoli();
            }
            else{
                $listaArticoliController->method_not_allowed();
            }
            break;
        case '/articoli/carenti':
            if ($method === 'POST') {
                $listaArticoliController->get_articoli_qt_bassa();
            }
            else{
                $listaArticoliController->method_not_allowed();
            }
            break;
        case '/articoli/perNome':
            if ($method === 'POST') {
                $articoliPerController->get_articoli_per_nome();
            }
            else{
                $articoliPerController->method_not_allowed();
            }
            break;
        case '/articoli/perScaffale':
            if ($method === 'POST') {
                $articoliPerController->get_articoli_per_scaffale();
            }
            else{
                $articoliPerController->method_not_allowed();
            }
            break;
        case '/articolo/quantita/aggiungi':
            if ($method === 'PUT') {
                $articoliQuantitaController->aggiungi();
            }
            else{
                $articoliQuantitaController->method_not_allowed();
            }
            break;
        case '/articolo/quantita/diminuisci':
            if ($method === 'PUT') {
                $articoliQuantitaController->diminuisci();
            }
            else{
                $articoliQuantitaController->method_not_allowed();
            }
            break;
        case '/esportaStorico':
            if ($method === 'GET') {
                $esportaStoricoCSVController->esporta();
            }
            else{
                $esportaStoricoCSVController->method_not_allowed();
            }
            break;
        // --- SE NESSUNA ROTTA CORRISPONDE ---
        default:
            http_response_code(404);
            echo json_encode(["error" => "Endpoint non trovato", "path" => $path]);
            break;
    }
}
catch (\Throwable $e) {
    // Cattura globale degli errori non gestiti
    http_response_code(500);
    echo json_encode([
            "error" => "Errore interno del server", 
            "detail" => $e->getMessage(),
            "file" => $e->getFile(),     // <--- AGGIUNGI QUESTO
            "line" => $e->getLine()      // <--- E QUESTO
        ]);
    }
?>