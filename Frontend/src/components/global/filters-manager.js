export class FilterManager {
    constructor(onFilterChange = null) {
        this.inputNome = document.getElementById('filtro-nome');
        this.inputTipologia = document.getElementById('filtro-tipologia');
        this.inputAttrNome = document.getElementById('filtro-attr-nome');
        this.inputAttrValore = document.getElementById('filtro-attr-valore');
        this.containerAttrValore = document.getElementById('filter-bar-valore-container');

        if(this.inputAttrNome) this.inputAttrNome.setAttribute('autocomplete', 'off');
        if(this.inputAttrValore) this.inputAttrValore.setAttribute('autocomplete', 'off');

        this.onFilterChange = onFilterChange;
        this.inizializzazioneEvents();
    }

    inizializzazioneEvents() {
        if (this.inputAttrNome && this.containerAttrValore) {
            this.inputAttrNome.addEventListener('input', (e) => {
                if (e.target.value.trim() !== '') {
                    this.containerAttrValore.style.display = 'flex';
                } else {
                    this.containerAttrValore.style.display = 'none';
                    this.inputAttrValore.value = ''; 
                }
                this.inputAttrValore.textContent = "";
                this.triggerChange(); 
            });
        }

        const inputs = [this.inputNome, this.inputTipologia, this.inputAttrValore];
        inputs.forEach(input => {
            if (input) input.addEventListener('input', () => this.triggerChange());
        });
    }

    triggerChange() {
        if (typeof this.onFilterChange === 'function') {
            this.onFilterChange(this.getValues());
        }
    }

    getValues() {
        return {
            nome: this.inputNome ? this.inputNome.value.trim() : '',
            tipologia: this.inputTipologia ? this.inputTipologia.value.trim() : '',
            attributoNome: this.inputAttrNome ? this.inputAttrNome.value.trim() : '',
            attributoValore: this.inputAttrValore ? this.inputAttrValore.value.trim() : ''
        };
    }

    filtraArray(articoli) {
        if (!articoli || articoli.length === 0) return [];

        const filtri = this.getValues();
        const valNome =  filtri.nome.toLowerCase();
        const valTip =  filtri.tipologia.toLowerCase();
        const valAttrNome = filtri.attributoNome.toLowerCase();
        const valAttrValore = filtri.attributoValore.toLowerCase();

        // Aggiorna la tendina dei valori dinamicamente basandosi su quello che cerchi
        this.popolaFiltroValori(articoli, valAttrNome);

        return articoli.filter(art => {
            const nomeArt = (art.nomeArticolo || "").toLowerCase();
            const nomeTipologia = (art.nomeTipologia || "").toLowerCase();

            const matchNome = valNome === '' || nomeArt.includes(valNome);
            const matchTipologia = valTip === '' || nomeTipologia.includes(valTip);
            
            let matchAttributi = true; 
            if (valAttrNome !== '') {
                const attrTrovato = art.attributi && art.attributi.find(a => a.nome.toLowerCase().includes(valAttrNome));
                if (!attrTrovato) {
                    matchAttributi = false; 
                } else if (valAttrValore !== '') {
                    if (!attrTrovato.valore.toLowerCase() === valAttrValore) matchAttributi = false; 
                }
            }
            return matchNome && matchTipologia && matchAttributi;
        });
    }

    popolaFiltriBase(articoli) {
        const setNomi = new Set();
        const setTipologie = new Set();
        const setAttrNomi = new Set();

        articoli.forEach(art => {
            if (art.nomeArticolo) setNomi.add(art.nomeArticolo);
            if (art.nomeTipologia) setTipologie.add(art.nomeTipologia);
            if (art.attributi && Array.isArray(art.attributi)) {
                art.attributi.forEach(attr => { if (attr.nome) setAttrNomi.add(attr.nome); });
            }
        });

        this._riempiDatalist('lista-nomi', setNomi);
        this._riempiDatalist('lista-tipologie', setTipologie);
        this._riempiDatalist('lista-attributi', setAttrNomi);
    }

    popolaFiltroValori(articoli, attributo) {
        const datalistValori = document.getElementById('lista-valori');
        if (!datalistValori) return;
        datalistValori.replaceChildren(); 
        if (!attributo) return;

        const setValori = new Set();
        articoli.forEach(art => {
            if (art.attributi && Array.isArray(art.attributi)) {
                art.attributi.forEach(attr => {
                    if (attr.nome.toLowerCase().includes(attributo) && attr.valore) {
                        setValori.add(attr.valore);
                    }
                });
            }
        });

        Array.from(setValori).sort().forEach(valore => {
            const opt = document.createElement('option');
            opt.value = valore;
            datalistValori.appendChild(opt);
        });
    }
    
    _riempiDatalist(id, setDati) {
        const datalist = document.getElementById(id);
        if (!datalist) return;
        datalist.replaceChildren(); 

        Array.from(setDati).sort().forEach(valore => {
            const opt = document.createElement('option');
            opt.value = valore;
            datalist.appendChild(opt);
        });
    }
}