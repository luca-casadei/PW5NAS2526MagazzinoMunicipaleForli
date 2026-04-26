<div class="filter-bar-container" aria-label="Filtri di ricerca articoli">
    
    <div class="filter-bar-group">
        <label for="filtro-nome">Nome Articolo</label>
        <input type="text" id="filtro-nome" list="lista-nomi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-nomi">
        </datalist>
    </div>

    <div class="filter-bar-group">
        <label for="filtro-tipologia">Tipologia</label>
        <input type="text" id="filtro-tipologia" list="lista-tipologie" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-tipologie">
        </datalist>
    </div>

    <div class="filter-bar-group">
        <label for="filtro-attr-nome">Filtra per Attributo</label>
        <input type="text" id="filtro-attr-nome" list="lista-attributi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-attributi">
        </datalist>
    </div>

    <div class="filter-bar-group" id="filter-bar-valore-container" style="display: none;">
        <label for="filtro-attr-valore">Valore Attributo</label>
        <input type="text" id="filtro-attr-valore" list="lista-valori" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-valori">
        </datalist>
    </div>

</div>