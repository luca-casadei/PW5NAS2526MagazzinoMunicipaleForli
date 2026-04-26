import { ApiRequest } from "../../components/global/ApiRequest.js";

document.addEventListener('DOMContentLoaded', () => {
    const contenitoreArmadi = document.getElementById('contenitore-armadi');
    const btnNuovoArmadio = document.getElementById('btn-nuovo-armadio');
    
    // Elementi Modale Creazione
    const modalCrea = document.getElementById('modal-creazione');
    const tipoCreaText = document.getElementById('tipo-creazione-text');
    const btnAnnullaCrea = document.getElementById('btn-annulla-crea');
    const btnConfermaCrea = document.getElementById('btn-conferma-crea');

    let statoCreazione = {
        tipo: null, 
        armadioId: null 
    };

    // --- 1. SIMULAZIONE CHIAMATE API CONCATENATE ---
    async function caricaTuttoIlMagazzino() {
        try {
            // Sostituito innerHTML con manipolazione DOM
            contenitoreArmadi.replaceChildren();
            const pLoading = document.createElement('p');
            pLoading.className = 'loading-text';
            pLoading.textContent = 'Caricamento struttura...';
            contenitoreArmadi.appendChild(pLoading);
            
            // Chiamata 1: Ottieni Armadi
            const armadi = await getArmadi();
            contenitoreArmadi.replaceChildren();

            for (const armadio of armadi) {
                const armadioElement = creaElementoArmadio(armadio);
                contenitoreArmadi.appendChild(armadioElement);

                // Chiamata 2: Ottieni Scaffali per questo Armadio
                const scaffali = await getScaffali(armadio.id);
                const containerScaffali = armadioElement.querySelector('.scaffale-container');

                for (const scaffale of scaffali) {
                    const scaffaleElement = creaElementoScaffale(scaffale);
                    containerScaffali.appendChild(scaffaleElement);

                    // Chiamata 3: Ottieni Articoli per questo Scaffale
                    const articoli = await getArticoliScaffale(scaffale.id);
                    const gridArticoli = scaffaleElement.querySelector('.articoli-scaffale-grid');
                    
                    articoli.forEach(art => {
                        gridArticoli.appendChild(creaCardArticolo(art));
                    });
                }
            }
        } catch (error) {
            // Sostituito innerHTML con manipolazione DOM
            contenitoreArmadi.replaceChildren();
            const pError = document.createElement('p');
            pError.className = 'error-text text-danger';
            pError.textContent = 'Errore nel caricamento della struttura magazzino.';
            contenitoreArmadi.appendChild(pError);
        }
    }

    // --- 2. FUNZIONI DI CREAZIONE DOM ---
    function creaElementoArmadio(armadio) {
        const details = document.createElement('details');
        details.className = 'armadio-section';
        
        const summary = document.createElement('summary');
        summary.className = 'armadio-summary';
        summary.textContent = `Armadio Numero ${armadio.numero}`; 
        
        const content = document.createElement('div');
        content.className = 'scaffale-container';
        
        const btnAddScaffale = document.createElement('button');
        btnAddScaffale.className = 'btn-add-scaffale';
        btnAddScaffale.textContent = '+ Aggiungi Nuovo Scaffale';
        btnAddScaffale.onclick = () => apriModalCreazione('Scaffale', armadio.id);
        
        details.append(summary, content, btnAddScaffale);
        return details;
    }

    function creaElementoScaffale(scaffale) {
        const details = document.createElement('details');
        details.className = 'scaffale-section';
        
        const summary = document.createElement('summary');
        summary.className = 'scaffale-summary';
        summary.textContent = `Scaffale ${scaffale.numero}`;
        
        const grid = document.createElement('div');
        grid.className = 'articoli-scaffale-grid';
        
        details.append(summary, grid);
        return details;
    }

    function creaCardArticolo(art) {
        const card = document.createElement('article');
        card.className = 'articolo-mini-card';
        
        const h4 = document.createElement('h4');
        h4.textContent = art.nome;
        
        // Sostituito innerHTML con creazione elementi per la quantità
        const infoQt = document.createElement('p');
        infoQt.textContent = 'Qt: ';
        const spanQt = document.createElement('span');
        spanQt.className = 'badge-qt';
        spanQt.id = `qt-val-${art.id}`;
        spanQt.textContent = art.quantita;
        infoQt.appendChild(spanQt);
        
        const ul = document.createElement('ul');
        ul.className = 'attr-list';
        
        art.attributi.sort((a,b) => a.n.localeCompare(b.n)).forEach(attr => {
            const li = document.createElement('li');
            
            // Sostituito innerHTML e forte accessibilità con classe attr-label
            const spanLabel = document.createElement('span');
            spanLabel.className = 'attr-label';
            spanLabel.textContent = `${attr.n}: `;
            
            li.appendChild(spanLabel);
            li.appendChild(document.createTextNode(attr.v));
            
            ul.appendChild(li);
        });
        
        const controlli = document.createElement('div');
        controlli.className = 'controlli-qt';
        
        const btnMinus = document.createElement('button');
        btnMinus.className = 'btn-qt-minus';
        btnMinus.textContent = '-1';
        btnMinus.onclick = () => aggiornaQuantita(art.id, -1);
        
        const inputAdd = document.createElement('input');
        inputAdd.type = 'number';
        inputAdd.className = 'input-add-qt';
        inputAdd.value = 1;
        inputAdd.min = 1;
        
        const btnAdd = document.createElement('button');
        btnAdd.className = 'btn-primary btn-qt-add';
        btnAdd.textContent = 'Aggiungi';
        btnAdd.onclick = () => {
            const val = parseInt(inputAdd.value);
            if(val > 0) aggiornaQuantita(art.id, val);
        };
        
        controlli.append(btnMinus, inputAdd, btnAdd);
        card.append(h4, infoQt, ul, controlli);
        return card;
    }

    // --- 3. LOGICA CREAZIONE STRUTTURALE ---
    btnNuovoArmadio.addEventListener('click', () => {
        apriModalCreazione('Armadio');
    });

    function apriModalCreazione(tipo, armadioId = null) {
        statoCreazione.tipo = tipo;
        statoCreazione.armadioId = armadioId;
        
        tipoCreaText.textContent = tipo; 
        modalCrea.showModal();
    }

    btnAnnullaCrea.addEventListener('click', () => {
        modalCrea.close();
        statoCreazione = { tipo: null, armadioId: null };
    });

    btnConfermaCrea.addEventListener('click', async () => {
        btnConfermaCrea.disabled = true;
        const testoOriginale = btnConfermaCrea.textContent;
        btnConfermaCrea.textContent = 'Creazione in corso...';

        try {
            let service = '';
            let payload = {};

            if (statoCreazione.tipo === 'Armadio') {
                service = 'creaArmadio_service.php';
            } else if (statoCreazione.tipo === 'Scaffale') {
                service = 'creaScaffale_service.php';
                payload = { armadioId: statoCreazione.armadioId };
            }

            const responseJSON = await ApiRequest.request(service, 'POST', payload);
            
            if(responseJSON.status !== 'success') {
                throw new Error(responseJSON.message || "Errore imprevisto dal server.");
            }

            modalCrea.close();
            statoCreazione = { tipo: null, armadioId: null };
            
            caricaTuttoIlMagazzino();

        } catch (error) {
            alert("Errore durante la creazione: " + error.message);
        } finally {
            btnConfermaCrea.disabled = false;
            btnConfermaCrea.textContent = testoOriginale;
        }
    });

    // --- 4. LOGICA AZIONI ARTICOLI ---
    async function aggiornaQuantita(id, delta) {
        const span = document.getElementById(`qt-val-${id}`);
        let attuale = parseInt(span.textContent);
        let nuovo = attuale + delta;
        if (nuovo < 0) nuovo = 0;
        
        span.textContent = nuovo;
        span.style.color = delta > 0 ? 'var(--success-color)' : 'var(--danger-color)';
        setTimeout(() => span.style.color = 'var(--primary-color)', 500);
    }

    // --- 5. SIMULATORI API GET ---
    function getArmadi() {
        return new Promise(res => setTimeout(() => res([{id:1, numero:10}, {id:2, numero:11}]), 400));
    }
    function getScaffali(id) {
        return new Promise(res => setTimeout(() => res([{id:10, numero:1}, {id:11, numero:2}]), 300));
    }
    function getArticoliScaffale(id) {
        return new Promise(res => setTimeout(() => res([
            {id: 50, nome: "Viti 4x20", quantita: 500, attributi: [{n:"Materiale", v:"Acciaio"}]},
            {id: 51, nome: "Bulloni M8", quantita: 120, attributi: [{n:"Tipo", v:"Esagonale"}]}
        ]), 300));
    }

    // --- INIT ---
    caricaTuttoIlMagazzino();
});