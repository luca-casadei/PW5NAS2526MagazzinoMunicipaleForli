import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const contenitoreArmadi = document.getElementById('contenitore-armadi');
    const btnNuovoArmadio = document.getElementById('btn-nuovo-armadio');
    
    const modalCrea = document.getElementById('modal-creazione');
    const tipoCreaText = document.getElementById('tipo-creazione-text');
    const btnAnnullaCrea = document.getElementById('btn-annulla-crea');
    const btnConfermaCrea = document.getElementById('btn-conferma-crea');

    let statoCreazione = { tipo: null, armadioId: null };
    let strutturaMagazzino = []; 
    let tuttiGliArticoli = []; 

    const gestoreFiltri = new FilterManager(() => renderMagazzino());

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
                const scaffaleElement = creaElementoScaffale(scaffale, armadio.id);
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

    function creaElementoScaffale(scaffale, armadioId) {
        const details = document.createElement('details');
        details.className = 'scaffale-section';
        details.open = true; 
        const summary = document.createElement('summary');
        summary.className = 'scaffale-summary';
        summary.textContent = `Scaffale ${scaffale.numeroScaffale}`;
        
        const actionBar = document.createElement('div');
        actionBar.className = 'scaffale-action-bar';
        const btnVaiAdArticoli = document.createElement('button');
        btnVaiAdArticoli.className = 'btn-secondary btn-add-to-shelf'; 
        btnVaiAdArticoli.innerHTML = '📦 + Aggiungi Articolo a questo scaffale';
        btnVaiAdArticoli.onclick = () => { window.location.href = '/pages/gestisciArticoli/index_gestisciArticoli.php'; };
        
        actionBar.appendChild(btnVaiAdArticoli);
        const grid = document.createElement('div');
        grid.className = 'articoli-scaffale-grid';
        details.append(summary, actionBar, grid);
        return details;
    }

    function creaCardArticolo(art, armadioId, numeroScaffale) {
        const card = document.createElement('article');
        card.className = 'articolo-mini-card';
        
        const h4 = document.createElement('h4');
        h4.textContent = art.nomeArticolo; 
        
        // --- SEZIONE INFO (BADGE QUANTITA) ---
        const badgeContainer = document.createElement('div');
        badgeContainer.className = 'qt-badges-container';

        const infoQt = document.createElement('p');
        infoQt.textContent = 'Nuovi: ';
        const spanQt = document.createElement('span');
        spanQt.className = 'badge-qt';
        spanQt.id = `qt-val-${art.idArticolo}-${numeroScaffale}`; 
        spanQt.textContent = art.quantita; 
        infoQt.appendChild(spanQt);

        const infoQtUsata = document.createElement('p');
        infoQtUsata.textContent = 'Usati: ';
        const spanQtUsata = document.createElement('span');
        spanQtUsata.className = 'badge-qt badge-usata';
        spanQtUsata.id = `qt-usata-val-${art.idArticolo}-${numeroScaffale}`; 
        spanQtUsata.textContent = art.qtUsata; // ESEMPIO: art.qtUsata 
        
        infoQtUsata.appendChild(spanQtUsata);
        badgeContainer.append(infoQt, infoQtUsata);
        
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
        
        const divider = document.createElement('div');
        divider.className = 'card-divider';

        const lblStock = document.createElement('div');
        lblStock.className = 'section-label';
        lblStock.textContent = 'Modifica Nuovi:';
        
        const ctrlStock = document.createElement('div');
        ctrlStock.className = 'controlli-qt';
        const inStock = document.createElement('input');
        inStock.type = 'number'; inStock.className = 'input-add-qt'; inStock.value = 1; inStock.min = 1;
        
        const btnMinusStock = document.createElement('button');
        btnMinusStock.className = 'btn-danger btn-qt-action'; btnMinusStock.textContent = 'Rimuovi';
        btnMinusStock.onclick = (e) => {
            const val = parseInt(inStock.value, 10);
            if(val > 0) diminuisciQuantita(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        }
        const btnAddStock = document.createElement('button');
        btnAddStock.className = 'btn-primary btn-qt-action'; btnAddStock.textContent = 'Aggiungi';
        btnAddStock.onclick = (e) => {
            const val = parseInt(inStock.value, 10);
            if(val > 0) aggiungiQuantita(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        };
        ctrlStock.append(btnMinusStock, inStock, btnAddStock);

        const lblUsata = document.createElement('div');
        lblUsata.className = 'section-label section-usata';
        lblUsata.textContent = 'Modifica Usato';
        
        const ctrlUsata = document.createElement('div');
        ctrlUsata.className = 'controlli-qt';
        const inUsata = document.createElement('input');
        inUsata.type = 'number'; inUsata.className = 'input-add-qt'; inUsata.value = 1; inUsata.min = 1;
        
        const btnMinusUsata = document.createElement('button');
        btnMinusUsata.className = 'btn-danger btn-qt-action'; btnMinusUsata.textContent = 'Rimuovi'; // Togliamo dall'usato
        btnMinusUsata.onclick = (e) => {
            const val = parseInt(inUsata.value, 10);
            if(val > 0) diminuisciQuantitaUsata(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        }
        const btnAddUsata = document.createElement('button');
        btnAddUsata.className = 'btn-primary btn-qt-action btn-add-usata'; btnAddUsata.textContent = 'Aggiungi'; // Segnamo come usato
        btnAddUsata.onclick = (e) => {
            const val = parseInt(inUsata.value, 10);
            if(val > 0) aggiungiQuantitaUsata(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        };
        ctrlUsata.append(btnMinusUsata, inUsata, btnAddUsata);
        
        card.append(h4, badgeContainer, ul, divider, lblStock, ctrlStock, lblUsata, ctrlUsata);
        return card;
    }

    async function aggiungiQuantita(articoloId, armadioId, numeroScaffale, quantita, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const responseJSON = await ApiRequest.request('aumentaQuantita_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Errore.");
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita, 'qt-val-');
        } catch (error) { alert("Errore: " + error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function diminuisciQuantita(articoloId, armadioId, numeroScaffale, quantita , bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const responseJSON = await ApiRequest.request('dimiuisciQuantita_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Errore.");
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita * -1, 'qt-val-');
        } catch (error) { alert("Errore: " + error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function aggiungiQuantitaUsata(articoloId, armadioId, numeroScaffale, quantita, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const responseJSON = await ApiRequest.request('aggiungi_qt_usato_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Errore.");
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita, 'qt-usata-val-');
        } catch (error) { alert("Errore: " + error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function diminuisciQuantitaUsata(articoloId, armadioId, numeroScaffale, quantita , bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const responseJSON = await ApiRequest.request('diminuisci_qt_usato_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') throw new Error(responseJSON.message || "Errore.");
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita * -1, 'qt-usata-val-');
        } catch (error) { alert("Errore: " + error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
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
    function aggiornaFeedbackVisivo(articoloId, numeroScaffale, delta, prefix = 'qt-val-') {
        // Usa il prefisso per trovare l'ID corretto (usato o nuovo)
        const span = document.getElementById(`${prefix}${articoloId}-${numeroScaffale}`);
        if (!span) return;

        let attuale = parseInt(span.textContent, 10);
        let nuovo = attuale + delta;
        
        span.textContent = nuovo;
        
        const colorSuccess = prefix.includes('usata') ? '#d97706' : 'var(--success-color)';
        const altroSpanValore = prefix.includes('usata') ? document.getElementById(`qt-val-${articoloId}-${numeroScaffale}`).textContent : document.getElementById(`qt-usata-val-${articoloId}-${numeroScaffale}`).textContent;
        span.style.color = delta > 0 ? colorSuccess : 'var(--danger-color)';
        
        setTimeout(() => span.style.color = '', 1500);
        if(altroSpanValore == 0 && nuovo == 0)
            caricaTuttoIlMagazzino();
    }

    async function getArmadi() {
        const responseJSON = await ApiRequest.request('getArmadi_service.php', 'GET');
        if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati armadi non validi dal server");
        return responseJSON.body;
    }

    async function getScaffali(armadioId) {
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