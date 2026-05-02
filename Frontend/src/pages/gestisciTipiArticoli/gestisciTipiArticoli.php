<nav class="top-nav" aria-label="Navigazione secondaria">
    <a href="/index.php" class="btn-back" aria-label="Torna alla Home">
        <span aria-hidden="true">⬅️</span> Torna alla Home
    </a>
</nav>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Configurazione Articoli</h1>
        <p>Crea nuovi modelli di articoli o rimuovi quelli esistenti dal catalogo.</p>
    </header>

    <div class="split-layout">
        <section class="form-section" aria-labelledby="titolo-crea">
            <h2 id="titolo-crea">Crea Nuovo Modello</h2>
            
            <form id="form-crea-articolo" class="styled-form">
                <fieldset>
                    <legend>Dati Obbligatori</legend>
                    <div class="form-group">
                        <label for="nome-articolo">Nome Articolo *</label>
                        <input type="text" id="nome-articolo" name="nome" required aria-required="true" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="tipologia-selezionata">Tipologia *</label>
                        <select id="tipologia-selezionata" name="tipologiaId" required aria-required="true" class="form-input">
                            <option value="">Caricamento...</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Caratteristiche</legend>
                    <div id="contenitore-attributi" class="dynamic-attributes">
                        <!-- Riga obbligatoria allineata con Flexbox -->
                        <div class="attribute-row">
                            <input type="text" placeholder="Nome (es. Peso)" required class="form-input" aria-label="Nome della caratteristica obbligatoria">
                            <input type="text" placeholder="Valore (es. 5kg)" required class="form-input" aria-label="Valore della caratteristica obbligatoria">
                        </div>
                    </div>
                    <button type="button" id="btn-aggiungi-attributo" class="btn-secondary">
                        + Aggiungi Campo
                    </button>
                </fieldset>

                <button type="submit" class="btn-primary">Salva nel Catalogo</button>
            </form>
            <div id="msg-feedback" aria-live="polite"></div>
        </section>

        <section class="list-section" aria-labelledby="titolo-lista">
            <h2 id="titolo-lista">Modelli in Catalogo</h2>
            <?php
                require $root_dir . "/components/global/filters.php";
            ?>
            <div id="griglia-articoli" class="articles-grid" aria-live="polite">
                <p class="loading-text">Caricamento modelli...</p>
            </div>
        </section>
    </div>
</main>

<dialog id="modal-delete" class="modal-dialog" aria-labelledby="modal-titolo">
    <div class="modal-content">
        <h2 id="modal-titolo" class="text-danger">Conferma Eliminazione</h2>
        <p>Stai per eliminare definitivamente 
            <strong>
                <span id="nome-art-delete"></span>
            </strong> dal catalogo.
        </p>
        <p>L'azione è irreversibile. Vuoi procedere?</p>
        <div class="modal-actions">
            <button type="button" id="btn-cancel" class="btn-secondary">Annulla</button>
            <button type="button" id="btn-confirm" class="btn-danger">Elimina Modello</button>
        </div>
    </div>
</dialog>