"use strict";
import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const contenitoreArmadi = document.getElementById('contenitore-armadi');
    const btnNuovoArmadio = document.getElementById('btn-nuovo-armadio');
    
    const modalElimina = document.getElementById('modal-eliminazione');
    const tipoEliminaText = document.getElementById('tipo-eliminazione-text');
    const btnAnnullaElimina = document.getElementById('btn-annulla-elimina');
    const btnConfermaElimina = document.getElementById('btn-conferma-elimina');

    const modalErrore = document.getElementById('modal-errore');
    const testoErrore = document.getElementById('testo-errore');
    const btnChiudiErrore = document.getElementById('btn-chiudi-errore');

    let statoEliminazione = { tipo: null, armadioId: null, numeroScaffale: null };
    let strutturaMagazzino = []; 
    let tuttiGliArticoli = []; 

    const gestoreFiltri = new FilterManager(() => renderMagazzino());

    function showError(message) {
        testoErrore.textContent = message;
        modalErrore.showModal();
    }
    btnChiudiErrore.addEventListener('click', () => modalErrore.close());

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
            pError.className = 'text-danger';
            pError.textContent = 'Errore nel caricamento della struttura: ' + error.message;
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
        
        const titleSpan = document.createElement('span');
        titleSpan.textContent = `Armadio (Identificativo: ${armadio.id})`;
        
        const actionsGroup = document.createElement('div');
        actionsGroup.className = 'summary-actions';
        
        const btnDelete = document.createElement('button');
        btnDelete.className = 'btn-delete';
        btnDelete.innerHTML = '🗑️';
        btnDelete.setAttribute('aria-label', `Elimina Armadio ${armadio.id}`);
        btnDelete.onclick = (e) => {
            e.preventDefault(); 
            e.stopPropagation();
            apriDialogElimina('Armadio', armadio.id);
        };
        actionsGroup.appendChild(btnDelete);
        summary.append(titleSpan, actionsGroup);
        
        const content = document.createElement('div');
        content.className = 'scaffale-container';
        
        const btnAddScaffale = document.createElement('button');
        btnAddScaffale.className = 'btn-add-scaffale';
        btnAddScaffale.textContent = '+ Aggiungi Nuovo Scaffale';
        btnAddScaffale.onclick = async () => {
            try {
                const res = await ApiRequest.request('creaScaffale_service.php', 'POST', { armadioId: armadio.id });
                if(res.status !== 'success') throw new Error(res.message);
                caricaTuttoIlMagazzino();
            } catch (err) { showError(err.message); }
        };
        
        details.append(summary, content, btnAddScaffale);
        return details;
    }

    function creaElementoScaffale(scaffale, armadioId) {
        const details = document.createElement('details');
        details.className = 'scaffale-section';
        details.open = true; 
        
        const summary = document.createElement('summary');
        summary.className = 'scaffale-summary';
        
        const titleSpan = document.createElement('span');
        titleSpan.textContent = `Scaffale (Identificativo: ${scaffale.numeroScaffale})`;

        const actionsGroup = document.createElement('div');
        actionsGroup.className = 'summary-actions';
        
        const btnDelete = document.createElement('button');
        btnDelete.className = 'btn-delete';
        btnDelete.innerHTML = '🗑️';
        btnDelete.setAttribute('aria-label', `Elimina Scaffale ${scaffale.numeroScaffale}`);
        btnDelete.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            apriDialogElimina('Scaffale', armadioId, scaffale.numeroScaffale);
        };
        actionsGroup.appendChild(btnDelete);
        summary.append(titleSpan, actionsGroup);
        
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
        
        const badgeContainer = document.createElement('div');
        badgeContainer.className = 'qt-badges-container';

        const infoQt = document.createElement('p');
        infoQt.textContent = 'Giacenza: ';
        const spanQt = document.createElement('span');
        spanQt.className = 'badge-qt';
        spanQt.id = `qt-val-${art.idArticolo}-${numeroScaffale}`; 
        spanQt.textContent = art.quantita; 
        infoQt.appendChild(spanQt);

        const infoQtUsata = document.createElement('p');
        infoQtUsata.textContent = 'Consumati: ';
        const spanQtUsata = document.createElement('span');
        spanQtUsata.className = 'badge-qt badge-usata';
        spanQtUsata.id = `qt-usata-val-${art.idArticolo}-${numeroScaffale}`; 
        spanQtUsata.textContent = art.qtUsata; 
        
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
        lblStock.textContent = 'Modifica quantità nuovo:';
        
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
        lblUsata.textContent = 'Modifica quantità usato';
        
        const ctrlUsata = document.createElement('div');
        ctrlUsata.className = 'controlli-qt';
        const inUsata = document.createElement('input');
        inUsata.type = 'number'; inUsata.className = 'input-add-qt'; inUsata.value = 1; inUsata.min = 1;
        
        const btnMinusUsata = document.createElement('button');
        btnMinusUsata.className = 'btn-danger btn-qt-action'; btnMinusUsata.textContent = 'Rimuovi'; 
        btnMinusUsata.onclick = (e) => {
            const val = parseInt(inUsata.value, 10);
            if(val > 0) diminuisciQuantitaUsata(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        }
        const btnAddUsata = document.createElement('button');
        btnAddUsata.className = 'btn-primary btn-qt-action btn-add-usata'; btnAddUsata.textContent = 'Aggiungi'; 
        btnAddUsata.onclick = (e) => {
            const val = parseInt(inUsata.value, 10);
            if(val > 0) aggiungiQuantitaUsata(art.idArticolo, armadioId, numeroScaffale, val, e.target);
        };
        ctrlUsata.append(btnMinusUsata, inUsata, btnAddUsata);
        
        card.append(h4, badgeContainer, ul, divider, lblStock, ctrlStock, lblUsata, ctrlUsata);
        return card;
    }

    btnNuovoArmadio.addEventListener('click', async () => {
        try {
            const res = await ApiRequest.request('creaArmadio_service.php', 'POST', {});
            if(res.status !== 'success') throw new Error(res.message);
            caricaTuttoIlMagazzino();
        } catch (err) { showError("Impossibile creare armadio: " + err.message); }
    });

    function apriDialogElimina(tipo, armadioId, numeroScaffale = null) {
        statoEliminazione = { tipo, armadioId, numeroScaffale };
        tipoEliminaText.textContent = tipo === 'Armadio' ? `Armadio N. ${armadioId}` : `Scaffale N. ${numeroScaffale} (Armadio ${armadioId})`; 
        modalElimina.showModal();
    }

    btnAnnullaElimina.addEventListener('click', () => {
        modalElimina.close();
        statoEliminazione = { tipo: null, armadioId: null, numeroScaffale: null };
    });

    btnConfermaElimina.addEventListener('click', async () => {
        btnConfermaElimina.disabled = true;
        const testoOriginale = btnConfermaElimina.textContent;
        btnConfermaElimina.textContent = 'Eliminazione...';

        try {
            let service = '';
            let payload = {};

            if (statoEliminazione.tipo === 'Armadio') {
                service = 'elimina_armadio_service.php'; 
                payload = { armadioId: parseInt(statoEliminazione.armadioId, 10) };
            } else if (statoEliminazione.tipo === 'Scaffale') {
                service = 'elimina_scaffale_service.php'; 
                payload = { 
                    armadioId: parseInt(statoEliminazione.armadioId, 10),
                    numScaffale: parseInt(statoEliminazione.numeroScaffale, 10) 
                };
            }

            const res = await ApiRequest.request(service, 'DELETE', payload);
            if(res.status !== 'success') throw new Error(res.message);

            modalElimina.close();
            caricaTuttoIlMagazzino();
        } catch (error) {
            modalElimina.close(); // Chiudiamo prima la modale di elimina
            showError("Azione negata: " + error.message); // E mostriamo la modale di errore pulita!
        } finally {
            btnConfermaElimina.disabled = false;
            btnConfermaElimina.textContent = testoOriginale;
            statoEliminazione = { tipo: null, armadioId: null, numeroScaffale: null };
        }
    });

    async function aggiungiQuantita(articoloId, armadioId, numeroScaffale, quantita, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const res = await ApiRequest.request('aumentaQuantita_service.php', 'POST', payload);
            if(res.status !== 'success') throw new Error(res.message);
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita, 'qt-val-');
        } catch (error) { showError(error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function diminuisciQuantita(articoloId, armadioId, numeroScaffale, quantita , bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const res = await ApiRequest.request('dimiuisciQuantita_service.php', 'POST', payload);
            if(res.status !== 'success') throw new Error(res.message);
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita * -1, 'qt-val-');
        } catch (error) { showError(error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function aggiungiQuantitaUsata(articoloId, armadioId, numeroScaffale, quantita, bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const res = await ApiRequest.request('aggiungi_qt_usato_service.php', 'POST', payload);
            if(res.status !== 'success') throw new Error(res.message);
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita, 'qt-usata-val-');
        } catch (error) { showError(error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    async function diminuisciQuantitaUsata(articoloId, armadioId, numeroScaffale, quantita , bottoneCliccato) {
        if(bottoneCliccato) bottoneCliccato.disabled = true;
        try {
            const payload = { articoloId: parseInt(articoloId, 10), armadioId: parseInt(armadioId, 10), numeroScaffale: parseInt(numeroScaffale, 10), quantita: parseInt(quantita, 10) };
            const res = await ApiRequest.request('diminuisci_qt_usato_service.php', 'POST', payload);
            if(res.status !== 'success') throw new Error(res.message);
            aggiornaFeedbackVisivo(articoloId, numeroScaffale, quantita * -1, 'qt-usata-val-');
        } catch (error) { showError(error.message); } finally { if(bottoneCliccato) bottoneCliccato.disabled = false; }
    }

    function aggiornaFeedbackVisivo(articoloId, numeroScaffale, delta, prefix = 'qt-val-') {
        const span = document.getElementById(`${prefix}${articoloId}-${numeroScaffale}`);
        if (!span) return;
        
        let attuale = parseInt(span.textContent, 10) || 0;
        let nuovo = attuale + delta;
        
        span.textContent = nuovo;
        
        const colorSuccess = prefix.includes('usata') ? '#d97706' : 'var(--success-color)';
        span.style.color = delta > 0 ? colorSuccess : 'var(--danger-color)';
        
        setTimeout(() => {
            span.style.color = '';
            let valNuovi = 0;
            let valUsati = 0;
            if (prefix === 'qt-val-') {
                valNuovi = nuovo; 
                const spanUsati = document.getElementById(`qt-usata-val-${articoloId}-${numeroScaffale}`);
                valUsati = spanUsati ? parseInt(spanUsati.textContent, 10) : 0;
            } else {
                valUsati = nuovo; 
                const spanNuovi = document.getElementById(`qt-val-${articoloId}-${numeroScaffale}`);
                valNuovi = spanNuovi ? parseInt(spanNuovi.textContent, 10) : 0;
            }
            
            if (valNuovi === 0 && valUsati === 0) {
                caricaTuttoIlMagazzino();
            }
        }, 1500);
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