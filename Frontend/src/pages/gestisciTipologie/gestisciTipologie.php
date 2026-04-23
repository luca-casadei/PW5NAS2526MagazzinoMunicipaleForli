<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Gestione Tipologie</h1>
        <p>Aggiungi nuove categorie di articoli o rimuovi quelle obsolete.</p>
    </header>

    <div class="split-layout">
        <section class="form-section" aria-labelledby="titolo-crea">
            <h2 id="titolo-crea">Crea Tipologia</h2>
            
            <form id="form-crea-tipologia" class="styled-form">
                <fieldset>
                    <legend>Dati Tipologia</legend>
                    
                    <div class="form-group">
                        <label for="nome-tipologia">Nome Tipologia *</label>
                        <input type="text" id="nome-tipologia" name="nome" required aria-required="true" placeholder="es. Pantaloni, T-Shirt...">
                    </div>
                </fieldset>

                <button type="submit" class="btn-primary">Salva Tipologia</button>
            </form>
            <div id="msg-creazione" aria-live="polite" class="msg-feedback"></div>
        </section>

        <section class="list-section" aria-labelledby="titolo-lista">
            <h2 id="titolo-lista">Tipologie Esistenti</h2>
            
            <div id="lista-tipologie" class="tipologie-grid" aria-live="polite">
                <p class="loading-text">Caricamento tipologie in corso...</p>
            </div>
        </section>
    </div>
</main>

<dialog id="modal-eliminazione" class="modal-dialog" aria-labelledby="modal-titolo" aria-describedby="modal-desc">
    <div class="modal-content">
        <h2 id="modal-titolo" class="text-danger">⚠️ Attenzione!</h2>
        <p id="modal-desc">
            Stai per eliminare la tipologia <strong id="nome-tipologia-da-eliminare"></strong>. <br>
            Questa azione <strong>eliminerà irreversibilmente tutti gli articoli</strong> associati a questa tipologia. Vuoi davvero procedere?
        </p>
        <div class="modal-actions">
            <button type="button" id="btn-annulla-elimina" class="btn-secondary">Annulla</button>
            <button type="button" id="btn-conferma-elimina" class="btn-danger">Sì, Elimina Tutto</button>
        </div>
    </div>
</dialog>