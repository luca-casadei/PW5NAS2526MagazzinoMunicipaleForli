"use strict";
import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const gridArticoli = document.getElementById('griglia-articoli');
    const chkbSoglia = document.getElementById('chk-soglia');
    const valSoglia = document.getElementById('val-soglia');
    
    let tuttiGliArticoli = []; 

    const gestoreFiltri = new FilterManager(() => {
        const articoliFiltrati = gestoreFiltri.filtraArray(tuttiGliArticoli);
        mostraArticoli(articoliFiltrati);
    });

    chkbSoglia.addEventListener('change', () => {
        valSoglia.disabled = !chkbSoglia.checked;
        caricaArticoli(); 
    });

    valSoglia.addEventListener('change', () => {
        if (chkbSoglia.checked) caricaArticoli();
    });

    async function caricaArticoli() {
        try {
            gridArticoli.replaceChildren(); 
            const pLoading = document.createElement('p');
            pLoading.className = 'loading-text';
            pLoading.textContent = 'Caricamento articoli in corso...';
            gridArticoli.appendChild(pLoading);

            let responseJSON;
            if (chkbSoglia.checked) {
                // FIX: Trasformiamo la soglia in intero
                const soglia = parseInt(valSoglia.value, 10);
                const $service = 'articoli_qtBasse_service.php';
                responseJSON = await ApiRequest.request($service, 'POST', { qtMinima: soglia });
            } else {
                const $service = 'articoli_service.php';
                responseJSON = await ApiRequest.request($service, 'GET');
            }

            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati articoli non validi");

            tuttiGliArticoli = responseJSON.body;
            gestoreFiltri.popolaFiltriBase(tuttiGliArticoli);
            
            const filtratiIniziali = gestoreFiltri.filtraArray(tuttiGliArticoli);
            mostraArticoli(filtratiIniziali);

        } catch (error) {
            gridArticoli.replaceChildren();
            const errP = document.createElement('p');
            errP.className = 'error-text text-danger';
            errP.textContent = 'Errore nel caricamento degli articoli.';
            gridArticoli.appendChild(errP);
        }
    }

    function mostraArticoli(articoliDaMostrare) {
        gridArticoli.replaceChildren(); 

        if (!articoliDaMostrare || articoliDaMostrare.length === 0) {
            const emptyMsg = document.createElement('p');
            emptyMsg.textContent = "Nessun articolo trovato per questi criteri.";
            gridArticoli.appendChild(emptyMsg);
            return;
        }

        articoliDaMostrare.sort((a, b) => a.nomeArticolo.localeCompare(b.nomeArticolo));

        articoliDaMostrare.forEach(art => {
            const card = document.createElement('article');
            card.className = 'article-card';
            
            const title = document.createElement('h3');
            title.textContent = art.nomeArticolo;

            const tag = document.createElement('span');
            tag.className = 'tag-tipologia';
            tag.textContent = art.nomeTipologia || "Sconosciuta";

            const qt = document.createElement('p');
            qt.className = 'qt-badge';
            qt.textContent = `Quantità in magazzino: ${art.quantitaTotale}`;

            const ul = document.createElement('ul');
            ul.className = 'attr-list';

            // Rendering Attributi
            if (art.attributi && art.attributi.length > 0) {
                art.attributi.sort((a, b) => a.nome.localeCompare(b.nome)).forEach(attr => {
                    const li = document.createElement('li');
                    const spanLabel = document.createElement('span');
                    spanLabel.className = 'attr-label';
                    spanLabel.textContent = `${attr.nome}: `;
                    li.append(spanLabel, document.createTextNode(attr.valore));
                    ul.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'Nessun attributo specifico';
                ul.appendChild(li);
            }

            // Container Azioni
            const actionRow = document.createElement('div');
            actionRow.className = 'article-actions';

            const btnLocalizza = document.createElement('button');
            btnLocalizza.className = 'btn-secondary';
            btnLocalizza.textContent = '📍 Localizza';
            btnLocalizza.addEventListener('click', () => localizza(art.idArticolo));

            const btnAggiungi = document.createElement('button');
            btnAggiungi.className = 'btn-primary';
            btnAggiungi.textContent = '➕ Aggiungi';
            btnAggiungi.addEventListener('click', () => mostraFormAggiunta(art.idArticolo));

            actionRow.append(btnLocalizza, btnAggiungi);

            // Container per i feedback dinamici
            const resultContainer = document.createElement('div');
            resultContainer.id = `loc-res-${art.idArticolo}`;

            const addContainer = document.createElement('div');
            addContainer.id = `add-loc-${art.idArticolo}`;

            card.append(title, tag, qt, ul, actionRow, resultContainer, addContainer);
            gridArticoli.appendChild(card);
        });
    }

    async function localizza(articoloId) {
        const resultContainer = document.getElementById(`loc-res-${articoloId}`);
        const addContainer = document.getElementById(`add-loc-${articoloId}`);
        
        if(addContainer) addContainer.innerHTML = '';
        if(resultContainer.innerHTML !== '' && !resultContainer.innerHTML.includes('Ricerca')) {
            resultContainer.innerHTML = ''; return;
        }

        resultContainer.innerHTML = '<div class="loc-result">Ricerca in corso...</div>';

        try {
            const idIntero = parseInt(articoloId, 10);
            const resp = await ApiRequest.request('loc_art_service.php', 'POST', { articoloId: idIntero });
            const posizioni = resp?.body || [];

            resultContainer.replaceChildren();
            if (posizioni.length === 0) {
                const div = document.createElement('div');
                div.className = 'loc-result error-loc';
                div.textContent = 'Articolo non presente in nessun armadio.';
                resultContainer.appendChild(div);
                return;
            }

            const successDiv = document.createElement('div');
            successDiv.className = 'loc-result';
            successDiv.innerHTML = '<span class="attr-label">Trovato in:</span>';
            
            const ul = document.createElement('ul');
            posizioni.forEach(pos => {
                const li = document.createElement('li');
                li.textContent = `Armadio Id:${pos.armadioId}, Scaffale Id:${pos.numeroScaffale}`;
                ul.appendChild(li);
            });

            successDiv.appendChild(ul);
            resultContainer.appendChild(successDiv);
        } catch (e) {
            resultContainer.innerHTML = '<div class="loc-result error-loc">Errore localizzazione.</div>';
        }
    }

    async function mostraFormAggiunta(articoloId) {
        const addContainer = document.getElementById(`add-loc-${articoloId}`);
        const resultContainer = document.getElementById(`loc-res-${articoloId}`);

        if(resultContainer) resultContainer.innerHTML = '';
        if(addContainer.innerHTML !== '') { addContainer.innerHTML = ''; return; }

        addContainer.innerHTML = '<div class="loc-result">Caricamento magazzino...</div>';

        try {
            const idIntero = parseInt(articoloId, 10);
            const locResp = await ApiRequest.request('loc_art_service.php', 'POST', { articoloId: idIntero });
            const posizioniAttuali = locResp?.body || []; 
            const armadi = await getArmadi();
            
            addContainer.replaceChildren();

            const formDiv = document.createElement('div');
            formDiv.className = 'add-to-shelf-form'; 

            const title = document.createElement('strong');
            title.textContent = 'Nuova posizione:';

            const selArmadio = document.createElement('select');
            selArmadio.className = 'filter-bar-input';
            selArmadio.innerHTML = '<option value="">-- Seleziona Armadio --</option>';
            
            armadi.forEach(a => {
                const idVero = a.idArmadio || a.id; 
                const num = a.numero || idVero || "Sconosciuto";
                selArmadio.innerHTML += `<option value="${idVero}">Armadio Id:${num}</option>`;
            });

            const selScaffale = document.createElement('select');
            selScaffale.className = 'filter-bar-input';
            selScaffale.innerHTML = '<option value="">-- Seleziona Scaffale --</option>';
            selScaffale.disabled = true;

            const qtRow = document.createElement('div');
            qtRow.className = 'qt-row';
            const inputQt = document.createElement('input');
            inputQt.type = 'number'; 
            inputQt.className = 'filter-bar-input qt-input';
            inputQt.value = 1; 
            inputQt.min = 1;
            qtRow.innerHTML = '<label>Quantità:</label>';
            qtRow.appendChild(inputQt);

            const btnSave = document.createElement('button');
            btnSave.className = 'btn-primary';
            btnSave.textContent = 'Salva Posizione';

            // Caricamento dinamico scaffali
            selArmadio.addEventListener('change', async (e) => {
                if (!e.target.value) { 
                    selScaffale.innerHTML = '<option value="">-- Seleziona Scaffale --</option>';
                    selScaffale.disabled = true; 
                    return; 
                }
                
                selScaffale.innerHTML = '<option value="">Caricamento...</option>';
                selScaffale.disabled = true;
                
                try {
                    const scaffali = await getScaffali(e.target.value);
                    
                    selScaffale.innerHTML = '<option value="">-- Seleziona Scaffale --</option>';
                    
                    scaffali.forEach(s => {
                        const numScaffale = s.numeroScaffale || s.numero || s.id; 
                        
                        const isPresente = posizioniAttuali.some(pos => 
                            String(pos.armadio) === String(e.target.value) && String(pos.scaffale) === String(numScaffale)
                        );
                        
                        const disabled = isPresente ? 'disabled' : '';
                        const suffix = isPresente ? ' (Già presente)' : '';
                        
                        selScaffale.innerHTML += `<option value="${numScaffale}" ${disabled}>Scaffale Id:${numScaffale}${suffix}</option>`;
                    });
                    
                    selScaffale.disabled = false;
                } catch (err) {
                    selScaffale.innerHTML = '<option value="">Errore caricamento</option>';
                    console.error("Errore caricamento scaffali:", err);
                }
            });

            btnSave.addEventListener('click', async () => {
                if(!selArmadio.value || !selScaffale.value) return alert("Seleziona una posizione valida.");
                btnSave.disabled = true;
                try {
                    await salvaNuovaPosizione(articoloId, selArmadio.value, selScaffale.value, inputQt.value);
                    formDiv.className = 'msg-success-inline';
                    formDiv.textContent = '✅ Aggiunto con successo!';
                    setTimeout(() => { addContainer.innerHTML = ''; localizza(articoloId); }, 1500);
                } catch(err) { alert(err.message); btnSave.disabled = false; }
            });

            formDiv.append(title, selArmadio, selScaffale, qtRow, btnSave);
            addContainer.appendChild(formDiv);
        } catch (err) {
            addContainer.innerHTML = `<div class="loc-result error-loc">Errore dati.</div>`;
        }
    }

    async function getArmadi() {
        const res = await ApiRequest.request('getArmadi_service.php', 'GET');
        return res?.body || [];
    }
    
    async function getScaffali(id) {
        const idIntero = parseInt(id, 10);
        const res = await ApiRequest.request('getScaffali_service.php', 'POST', { armadioId: idIntero });
        if (!res || !Array.isArray(res.body)) {
            throw new Error("Formato dati scaffali non valido");
        }
        return res.body;
    }
    
    async function salvaNuovaPosizione(artId, armId, scafNum, qt) {
        const payload = { 
            articoloId: parseInt(artId, 10), 
            armadioId: parseInt(armId, 10), 
            numeroScaffale: parseInt(scafNum, 10), 
            quantita: parseInt(qt, 10) 
        };
        const res = await ApiRequest.request('aumentaQuantita_service.php', 'POST', payload);
        if(res.status !== 'success') throw new Error(res.message);
    }

    caricaArticoli();
});