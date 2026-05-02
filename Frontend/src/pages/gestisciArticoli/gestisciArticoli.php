<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>

<main class="dashboard-container">
    <!-- MODIFICA: Header diviso in due colonne (Testo a sx, Bottone a dx) -->
    <header class="page-header header-with-action">
        <div class="header-texts">
            <h1>Articoli in Magazzino</h1>
            <p>Consulta l'elenco degli articoli presenti, verifica le quantità e localizza la loro posizione.</p>
        </div>
        <div class="header-actions">
            <!-- Il bottone sfrutta la classe btn-primary per mantenere lo stile uniforme -->
            <a href="/pages/gestisciTipiArticoli/index_gestisciTipiArticoli.php" class="btn-primary btn-nuovo-tipo">
                <span class="emoji-bianca">➕</span>Crea Nuovo Modello
            </a>
        </div>
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