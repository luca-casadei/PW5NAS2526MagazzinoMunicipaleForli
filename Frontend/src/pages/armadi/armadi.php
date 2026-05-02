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

    <div class="actions-bottom">
        <button id="btn-nuovo-armadio" class="btn-primary">+ Nuovo Armadio</button>
    </div>
</main>

<dialog id="modal-creazione" class="modal-dialog" aria-labelledby="modal-titolo-crea">
    <div class="modal-content">
        <h2 id="modal-titolo-crea" class="text-primary">Conferma Creazione</h2>
        <p>Stai per creare un nuovo <strong><span id="tipo-creazione-text"></span></strong> nel magazzino.</p>
        <p>L'azione è irreversibile e modificherà la struttura fisica a sistema. Vuoi procedere?</p>
        <div class="modal-actions">
            <button type="button" id="btn-annulla-crea" class="btn-secondary">Annulla</button>
            <button type="button" id="btn-conferma-crea" class="btn-primary">Sì, Crea Subito</button>
        </div>
    </div>
</dialog>