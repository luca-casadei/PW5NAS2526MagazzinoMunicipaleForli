<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Articoli in Magazzino</h1>
        <p>Consulta l'elenco degli articoli presenti, verifica le quantità e localizza la loro posizione.</p>
    </header>

    <div class="soglia-container" role="group" aria-label="Filtro scorte in esaurimento">
        <div class="soglia-controlli">
            <input type="checkbox" id="chk-soglia" name="chk-soglia">
            <label for="chk-soglia">Mostra solo articoli in esaurimento</label>
            
            <div class="soglia-input-group">
                <label for="val-soglia" class="sr-only">Soglia quantità</label>
                <input type="number" id="val-soglia" min="0" value="10" disabled>
            </div>
        </div>
    </div>

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