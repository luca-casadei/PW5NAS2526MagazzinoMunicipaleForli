<section class="filters-section" aria-labelledby="intestazione-filtri">
    
    <header class="filter-header">
        <h2 id="intestazione-filtri" class="filter-title">
            <span aria-hidden="true">🔍</span> Filtri
        </h2>
    </header>

    <div class="filter-bar-container">
        
        <div class="filter-bar-group">
            <label for="filtro-nome">Filtro per Nome Articolo</label>
            <input type="text" id="filtro-nome" list="lista-nomi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
            <datalist id="lista-nomi"></datalist>
        </div>

        <div class="filter-bar-group">
            <label for="filtro-tipologia" title="filtro ceh raoppresenart">Filtro per Tipologia</label>
            <input type="text" id="filtro-tipologia" list="lista-tipologie" class="filter-bar-input" placeholder="Scrivi o seleziona...">
            <datalist id="lista-tipologie"></datalist>
        </div>

        <div class="filter-bar-group">
            <label for="filtro-attr-nome">Filtro per Caratteristiche</label>
            <input type="text" id="filtro-attr-nome" list="lista-attributi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
            <datalist id="lista-attributi"></datalist>
        </div>

        <div class="filter-bar-group" id="filter-bar-valore-container" style="display: none;">
            <label for="filtro-attr-valore">Filtro per Valore Caratteristica</label>
            <input type="text" id="filtro-attr-valore" list="lista-valori" class="filter-bar-input" placeholder="Scrivi o seleziona...">
            <datalist id="lista-valori"></datalist>
        </div>

    </div>
</section>