<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Gestione Armadi e Scorte</h1>
        <p>Esplora il magazzino, controlla le quantità e aggiorna le giacenze in tempo reale.</p>
    </header>

    <?php
        require $root_dir . "/components/global/filters.php";
    ?>
    <section id="contenitore-armadi" class="armadi-layout" aria-live="polite">
        <p class="loading-text">Inizializzazione mappa magazzino...</p>
    </section>
</main>