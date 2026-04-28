import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const contenitoreArmadi = document.getElementById('contenitore-armadi');
    const btnNuovoArmadio = document.getElementById('btn-nuovo-armadio');
    
    const modalCrea = document.getElementById('modal-creazione');
    const tipoCreaText = document.getElementById('tipo-creazione-text');
    const btnAnnullaCrea = document.getElementById('btn-annulla-crea');
    const btnConfermaCrea = document.getElementById('btn-conferma-crea');

    let statoCreazione = {
        tipo: null, 
        armadioId: null 
    };

    let strutturaMagazzino = []; 
    let tuttiGliArticoli = []; 

    const gestoreFiltri = new FilterManager(() => {
        renderMagazzino();
    });

    async function caricaTuttoIlMagazzino() {
        try {
            contenitoreArmadi.replaceChildren();
            const pLoading = document.createElement('p');
            pLoading.className = 'loading-text';
            pLoading.textContent = 'Caricamento struttura dal server...';
            contenitoreArmadi.appendChild(pLoading);
            
            const armadi = await getArmadi();
            
            strutturaMagazzino = [];
            tuttiGliArticoli = [];

            for (const armadio of armadi) {
                const scaffali = await getScaffali(armadio.id);
                const armadioData = { ...armadio, scaffali: [] };

                for (const scaffale of scaffali) {
                    const articoli = await getArticoliScaffale(armadio.id, scaffale.numeroScaffale);
                    armadioData.scaffali.push({ ...scaffale, articoli: articoli });
                    
                    articoli.forEach(art => tuttiGliArticoli.push(art));
                }
                strutturaMagazzino.push(armadioData);
            }

            gestoreFiltri.popolaFiltriBase(tuttiGliArticoli);
            renderMagazzino();

        } catch (error) {
            contenitoreArmadi.replaceChildren();
            const pError = document.createElement('p');
            pError.className = 'error-text text-danger';
            pError.textContent = 'Errore nel caricamento della struttura magazzino: ' + error.message;
            contenitoreArmadi.appendChild(pError);
        }
    }

    function renderMagazzino() {
        contenitoreArmadi.replaceChildren();

        const articoliVisibili = gestoreFiltri.filtraArray(tuttiGliArticoli);
        const idVisibili = new Set(articoliVisibili.map(a => a.idArticolo));

        strutturaMagazzino.forEach(armadio => {
            const armadioElement = creaElementoArmadio(armadio);
            const containerScaffali = armadioElement.querySelector('.scaffale-container');

            armadio.scaffali.forEach(scaffale => {
                const scaffaleElement = creaElementoScaffale(scaffale);
                const gridArticoli = scaffaleElement.querySelector('.articoli-scaffale-grid');

                scaffale.articoli.forEach(art => {
                    if (idVisibili.has(art.idArticolo)) {
                        gridArticoli.appendChild(creaCardArticolo(art, armadio.id, scaffale.numeroScaffale));
                    }
                });

                containerScaffali.appendChild(scaffaleElement);
            });

            contenitoreArmadi.appendChild(armadioElement);
        });
    }

    function creaElementoArmadio(armadio) {
        const details = document.createElement('details');
        details.className = 'armadio-section';
        details.open = true; 
        
        const summary = document.createElement('summary');
        summary.className = 'armadio-summary';
        summary.textContent = `Armadio Numero ${armadio.id}`; 
        
        const content = document.createElement('div');
        content.className = 'scaffale-container';
        
        const btnAddScaffale = document.createElement('button');
        btnAddScaffale.className = 'btn-add-scaffale';
        btnAddScaffale.textContent = '+ Aggiungi Nuovo Scaffale';
        btnAddScaffale.onclick = () => apriDialogCreate('Scaffale', armadio.id);
        
        details.append(summary, content, btnAddScaffale);
        return details;
    }

    function creaElementoScaffale(scaffale) {
        const details = document.createElement('details');
        details.className = 'scaffale-section';
        details.open = true; 
        
        const summary = document.createElement('summary');
        summary.className = 'scaffale-summary';
        summary.textContent = `Scaffale ${scaffale.numeroScaffale}`;
        
        const grid = document.createElement('div');
        grid.className = 'articoli-scaffale-grid';
        
        details.append(summary, grid);
        return details;
    }

    // Riceve anche armadioId e numeroScaffale per poterli usare nei bottoni!
    function creaCardArticolo(art, armadioId, numeroScaffale) {
        const card = document.createElement('article');
        card.className = 'articolo-mini-card';
        
        const h4 = document.createElement('h4');
        h4.textContent = art.nomeArticolo; 
        
        const infoQt = document.createElement('p');
        infoQt.textContent = 'Qt: ';
        const spanQt = document.createElement('span');
        spanQt.className = 'badge-qt';
        // Aggiungo anche lo scaffale all'ID per evitare conflitti se lo stesso articolo è su due scaffali diversi!
        spanQt.id = `qt-val-${art.idArticolo}-${numeroScaffale}`; 
        spanQt.textContent = art.quantitaTotale; 
        infoQt.appendChild(spanQt);
        
        const ul = document.createElement('ul');
        ul.className = 'attr-list';
        
        if (art.attributi && Array.isArray(art.attributi)) {
            art.attributi.sort((a,b) => a.nome.localeCompare(b.nome)).forEach(attr => {
                const li = document.createElement('li');
                const spanLabel = document.createElement('span');
                spanLabel.className = 'attr-label';
                spanLabel.textContent = `${attr.nome}: `; 
                li.appendChild(spanLabel);
                li.appendChild(document.createTextNode(attr.valore)); 
                ul.appendChild(li);
            });
        }
        
        const controlli = document.createElement('div');
        controlli.className = 'controlli-qt';
        
        const btnMinus = document.createElement('button');
        btnMinus.className = 'btn-qt-minus';
        btnMinus.textContent = '-1';
        // Richiama la funzione specifica per diminuire
        btnMinus.onclick = (e) => diminuisciQuantita(art.idArticolo, armadioId, numeroScaffale, e.target);
        
        const inputAdd = document.createElement('input');
        inputAdd.type = 'number';
        inputAdd.className = 'input-add-qt';
        inputAdd.value = 1;
        inputAdd.min = 1;
        
        const btnAdd = document.createElement('button');
        btnAdd.className = 'btn-primary btn-qt-add';
        btnAdd.textContent = 'Aggiungi';
        btnAdd.onclick = (e) => {
            const val = parseInt(inputAdd.value, 10);
            if(val > 0) aggiungiQuantita(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        };
        
        controlli.append(btnMinus, inputAdd, btnAdd);
        card.append(h4, infoQt, ul, controlli);
        return card;
    }

    btnNuovoArmadio.addEventListener('click', () => {
        apriDialogCreate('Armadio');
    });

    function apriDialogCreate(tipo, armadioId = null) {
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
                // FIX: Trasformo in intero l'ID dell'armadio per creare lo scaffale
                payload = { armadioId: parseInt(statoCreazione.armadioId, 10) };
            }

            const responseJSON = await ApiRequest.request(service, 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Errore imprevisto dal server.");

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

    async function aggiungiQuantita(articoloId, armadioId, numeroScaffale, quantita, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;

        try {
            // FIX: Tutto convertito in interi
            const payload = { 
                articoloId: parseInt(articoloId, 10), 
                armadioId: parseInt(armadioId, 10),
                numeroScaffale: parseInt(numeroScaffale, 10),
                quantita: parseInt(quantita, 10)
            };
            
            const responseJSON = await ApiRequest.request('aumentaQuantita_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Impossibile aggiungere la quantità.");

            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita);
        } catch (error) {
            alert("Errore: " + error.message);
        } finally {
            if(bottoneCliccato) bottoneCliccato.disabled = false;
        }
    }

    async function diminuisciQuantita(articoloId, armadioId, numeroScaffale, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;

        try {
            // FIX: Tutto convertito in interi
            const payload = { 
                articoloId: parseInt(articoloId, 10), 
                armadioId: parseInt(armadioId, 10),
                numeroScaffale: parseInt(numeroScaffale, 10)
            };
            
            const responseJSON = await ApiRequest.request('dimiuisciQuantita_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Impossibile diminuire la quantità.");

            aggiornaFeedbackVisivo(articoloId, numeroScaffale, -1);

        } catch (error) {
            alert("Errore: " + error.message);
        } finally {
            if(bottoneCliccato) bottoneCliccato.disabled = false;
        }
    }

    function aggiornaFeedbackVisivo(articoloId, numeroScaffale, delta) {
        const span = document.getElementById(`qt-val-${articoloId}-${numeroScaffale}`);
        if (!span) return;

        let attuale = parseInt(span.textContent, 10);
        let nuovo = attuale + delta;
        if (nuovo < 0) nuovo = 0;
        
        span.textContent = nuovo;
        span.style.color = delta > 0 ? 'var(--success-color)' : 'var(--danger-color)';
        setTimeout(() => span.style.color = 'var(--primary-color)', 500);
    }

    async function getArmadi() {
        const responseJSON = await ApiRequest.request('getArmadi_service.php', 'GET');
        if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati armadi non validi dal server");
        return responseJSON.body;
    }

    async function getScaffali(armadioId) {
        // FIX: Trasformo in intero l'ID dell'armadio per prendere gli scaffali
        const idIntero = parseInt(armadioId, 10);
        const responseJSON = await ApiRequest.request('getScaffali_service.php', 'POST', { armadioId: idIntero });
        if(!responseJSON || !Array.isArray(responseJSON.body)) return []; 
        return responseJSON.body;
    }

    async function getArticoliScaffale(armadioId, numeroScaffale) {
        const payload = { 
            idArmadio: parseInt(armadioId, 10), 
            numScaffale: parseInt(numeroScaffale, 10) 
        };
        const responseJSON = await ApiRequest.request('getArticoliScaffale_service.php', 'POST', payload);
        if(!responseJSON || !Array.isArray(responseJSON.body)) return [];
        return responseJSON.body;
    }

    caricaTuttoIlMagazzino();
});