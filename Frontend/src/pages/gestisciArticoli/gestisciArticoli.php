<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Articoli in Magazzino</h1>
        <p>Consulta l'elenco degli articoli presenti, verifica le quantità e localizza la loro posizione.</p>
    </header>

    <?php
        require $root_dir . "/components/global/filters.php";
    ?>

    <section aria-labelledby="titolo-lista">
        <h2 id="titolo-lista" style="display: none;">Elenco Articoli</h2>
        
        <div id="griglia-articoli" class="articles-grid" aria-live="polite">
            <p class="loading-text">Caricamento articoli in corso...</p>
        </div>
    </section>
</main>