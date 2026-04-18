<nav class="top-nav" aria-label="Navigazione secondaria">
    <a href="/index.php" class="btn-back" aria-label="Torna alla Home">
        <span aria-hidden="true">⬅️</span> Torna alla Home
    </a>
</nav>

<main class="dashboard-container">
    <header class="page-header">
        <h1>Gestione Articoli</h1>
        <p>Aggiungi nuovi articoli o localizza quelli presenti in magazzino.</p>
    </header>

    <div class="split-layout">
        <section class="form-section" aria-labelledby="titolo-crea">
            <h2 id="titolo-crea">Crea Nuovo Articolo</h2>
            
            <form id="form-crea-articolo" class="styled-form">
                <fieldset>
                    <legend>Dati Obbligatori</legend>
                    
                    <div class="form-group">
                        <label for="nome-articolo">Nome Articolo *</label>
                        <input type="text" id="nome-articolo" name="nome" required aria-required="true">
                    </div>

                    <div class="form-group">
                        <label for="codice-articolo">Codice Identificativo *</label>
                        <input type="text" id="codice-articolo" name="codice" required aria-required="true">
                    </div>

                    <div class="form-group">
                        <label for="tipologia-selezionata">Tipologia *</label>
                        <select id="tipologia-selezionata" name="tipologiaId" required aria-required="true">
                            <option value="">Caricamento tipologie...</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Attributi Specifici (Facoltativi)</legend>
                    <div id="contenitore-attributi" class="dynamic-attributes">
                        </div>
                    <button type="button" id="btn-aggiungi-attributo" class="btn-secondary">
                        <span aria-hidden="true">+</span> Aggiungi Attributo
                    </button>
                </fieldset>

                <button type="submit" class="btn-primary">Salva Articolo</button>
            </form>
        </section>

        <section class="list-section" aria-labelledby="titolo-lista">
            <h2 id="titolo-lista">Articoli in Magazzino</h2>
            
            <div id="griglia-articoli" class="articles-grid" aria-live="polite">
                <p class="loading-text">Caricamento articoli in corso...</p>
            </div>
        </section>
    </div>
</main>