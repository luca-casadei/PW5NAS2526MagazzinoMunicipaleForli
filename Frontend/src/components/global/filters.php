<div class="filter-bar-container" aria-label="Filtri di ricerca articoli">
    
    <div class="filter-bar-group">
        <label for="filtro-nome">Nome Articolo</label>
        <input type="text" id="filtro-nome" list="lista-nomi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-nomi">
            <option value="Tutti"></option>
        </datalist>
    </div>

    <div class="filter-bar-group">
        <label for="filtro-tipologia">Tipologia</label>
        <input type="text" id="filtro-tipologia" list="lista-tipologie" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-tipologie">
            <option value="Tutti"></option>
        </datalist>
    </div>

    <div class="filter-bar-group">
        <label for="filtro-attr-nome">Filtra per Attributo</label>
        <input type="text" id="filtro-attr-nome" list="lista-attributi" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-attributi">
            <option value="Tutti"></option>
        </datalist>
    </div>

    <div class="filter-bar-group" id="filter-bar-valore-container" style="display: none;">
        <label for="filtro-attr-valore">Valore Attributo</label>
        <input type="text" id="filtro-attr-valore" list="lista-valori" class="filter-bar-input" placeholder="Scrivi o seleziona...">
        <datalist id="lista-valori">
            <option value="Tutti"></option>
        </datalist>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputAttrNome = document.getElementById('filtro-attr-nome');
    const containerAttrValore = document.getElementById('filter-bar-valore-container');
    const inputAttrValore = document.getElementById('filtro-attr-valore');

    if(inputAttrNome && containerAttrValore) {
        inputAttrNome.addEventListener('input', (e) => {
            // Se c'è del testo nel campo Attributo, mostra il campo Valore
            if (e.target.value.trim() !== '') {
                containerAttrValore.style.display = 'flex';
            } else {
                // Se viene svuotato, nascondi il campo Valore e resettalo
                containerAttrValore.style.display = 'none';
                inputAttrValore.value = '';
            }
        });
    }
});
</script>