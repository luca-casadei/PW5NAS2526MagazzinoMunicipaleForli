export class FilterManager{
    constructor(onFilterChange = null) {
        //prendo tutti filtri
        this.inputNome = document.getElementById('filtro-nome');
        this.inputTipologia = document.getElementById('filtro-tipologia');
        this.inputAttrNome = document.getElementById('filtro-attr-nome');
        this.inputAttrValore = document.getElementById('filtro-attr-valore');
        this.containerAttrValore = document.getElementById('filter-bar-valore-container');

        // callback (la funzione da chiamare quando i filtri cambiano)
        this.onFilterChange = onFilterChange;

        // init
        this.inizializzazioneEvents();
    }

    inizializzazioneEvents() {
        // mostra/nascondi il filtro valore dell'attributo (se non inserito non lo mostro)
        if (this.inputAttrNome && this.containerAttrValore) {
            this.inputAttrNome.addEventListener('input', (e) => {
                if (e.target.value.trim() !== '') {
                    this.containerAttrValore.style.display = 'flex';
                } else {
                    this.containerAttrValore.style.display = 'none';
                    this.inputAttrValore.value = ''; // Resetta il valore
                }
                this.triggerChange(); // cambiamento
            });
        }

        // event listener su tutti i filtri (nome attr gia fatto prima)
        const inputs = [this.inputNome, this.inputTipologia, this.inputAttrValore];
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', () => this.triggerChange());
            }
        });
    }

    // richiama la funzione della pagina che usa classe
    triggerChange() {
        if (typeof this.onFilterChange === 'function') {
            this.onFilterChange(this.getValues());
        }
    }

    // estrae lo stato attuale di tutti i filtri
    getValues() {
        return {
            nome: this.inputNome ? this.inputNome.value.trim() : '',
            tipologia: this.inputTipologia ? this.inputTipologia.value.trim() : '',
            attributoNome: this.inputAttrNome ? this.inputAttrNome.value.trim() : '',
            attributoValore: this.inputAttrValore ? this.inputAttrValore.value.trim() : ''
        };
    }
}
