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

<!-- Modale di Conferma Eliminazione -->
<dialog id="modal-eliminazione" class="modal-dialog" aria-labelledby="modal-titolo-elimina">
    <div class="modal-content">
        <h2 id="modal-titolo-elimina" class="text-danger">⚠️ Conferma Eliminazione</h2>
        <p>Stai per eliminare definitivamente questo <strong><span id="tipo-eliminazione-text"></span></strong>.</p>
        <p>Selezionando "Elimina", i dati verranno rimossi. Vuoi procedere?</p>
        <div class="modal-actions">
            <button type="button" id="btn-annulla-elimina" class="btn-secondary">Annulla</button>
            <button type="button" id="btn-conferma-elimina" class="btn-danger">Sì, Elimina</button>
        </div>
    </div>
</dialog>

<!-- Modale per gli Errori (Sostituisce gli Alert) -->
<dialog id="modal-errore" class="modal-dialog" aria-labelledby="modal-titolo-errore">
    <div class="modal-content border-top-danger">
        <h2 id="modal-titolo-errore" class="text-danger">Azione negata</h2>
        <p id="testo-errore" class="error-message-text"></p>
        <div class="modal-actions justify-center">
            <button type="button" id="btn-chiudi-errore" class="btn-secondary">Ho capito</button>
        </div>
    </div>
</dialog>