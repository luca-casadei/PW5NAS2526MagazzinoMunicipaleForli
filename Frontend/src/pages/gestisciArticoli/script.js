import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const gridArticoli = document.getElementById('griglia-articoli');
    const chkbSoglia = document.getElementById('chk-soglia');
    const valSoglia = document.getElementById('val-soglia');
    
    let tuttiGliArticoli = []; 

    // filtri
    const gestoreFiltri = new FilterManager(() => {
        const articoliFiltrati = gestoreFiltri.filtraArray(tuttiGliArticoli);
        //mostra a scermo card
        mostraArticoli(articoliFiltrati);
    });

    // soglie
    chkbSoglia.addEventListener('change', () => {
        valSoglia.disabled = !chkbSoglia.checked;
        caricaArticoli(); 
    });

    valSoglia.addEventListener('change', () => {
        if (chkbSoglia.checked) caricaArticoli();
    });

    // 
    async function caricaArticoli() {
        try {
            // visivo
            gridArticoli.replaceChildren(); 
            const pLoading = document.createElement('p');
            pLoading.className = 'loading-text';
            pLoading.textContent = 'Caricamento articoli in corso...';
            gridArticoli.appendChild(pLoading);

            // prendo dati
            let responseJSON;
            if (chkbSoglia.checked) {
                const soglia = valSoglia.value;
                const $service = 'articoli_qtBasse_service.php';
                responseJSON = await ApiRequest.request($service, 'POST', { qtMinima: soglia });
            } else {
                const $service = 'articoli_service.php';
                responseJSON = await ApiRequest.request($service, 'GET');
            }

            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati articoli non validi");

            tuttiGliArticoli = responseJSON.body;
            // popola filtri
            gestoreFiltri.popolaFiltriBase(tuttiGliArticoli);
            // filtra articoli
            const filtratiIniziali = gestoreFiltri.filtraArray(tuttiGliArticoli);
            mostraArticoli(filtratiIniziali);

        } catch (error) {
            gridArticoli.replaceChildren();
            const errP = document.createElement('p');
            errP.className = 'error-text text-danger';
            errP.textContent = 'Errore nel caricamento degli articoli.';
            gridArticoli.appendChild(errP);
            console.error(error);
        }
    }

    // mostra a schermo
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

            if (art.attributi && art.attributi.length > 0) {
                art.attributi.sort((a, b) => a.nome.localeCompare(b.nome));

                art.attributi.forEach(attr => {
                    const li = document.createElement('li');
                    
                    const spanLabel = document.createElement('span');
                    spanLabel.className = 'attr-label';
                    spanLabel.textContent = `${attr.nome}: `;
                    
                    li.appendChild(spanLabel);
                    li.appendChild(document.createTextNode(attr.valore));
                    ul.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'Nessun attributo specifico';
                ul.appendChild(li);
            }

            const btnLocalizza = document.createElement('button');
            btnLocalizza.className = 'btn-secondary btn-localizza';
            btnLocalizza.textContent = '📍 Localizza';
            btnLocalizza.addEventListener('click', () => localizza(art.idArticolo));

            const resultContainer = document.createElement('div');
            resultContainer.className = 'loc-result-container';
            resultContainer.id = `loc-res-${art.idArticolo}`;

            card.append(title, tag, qt, ul, btnLocalizza, resultContainer);
            gridArticoli.appendChild(card);
        });
    }

    // localizzazione articoli
    async function localizza(articoloId) {
        const resultContainer = document.getElementById(`loc-res-${articoloId}`);
        resultContainer.replaceChildren(); 
        
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'loc-result';
        loadingDiv.textContent = 'Ricerca in corso...';
        resultContainer.appendChild(loadingDiv);

        try {
            const servizio = 'loc_art_service.php';
            const responseJSON = await ApiRequest.request(servizio, 'POST', { articoloId: articoloId });
            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati localizzazione non validi");
            const posizioni = responseJSON.body || [];

            resultContainer.replaceChildren();
            if (posizioni.length === 0) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'loc-result error-loc'; 
                emptyDiv.textContent = 'Articolo non presente in nessun armadio.';
                resultContainer.appendChild(emptyDiv);
                return;
            }

            const successDiv = document.createElement('div');
            successDiv.className = 'loc-result';
            
            const spanLabel = document.createElement('span');
            spanLabel.className = 'attr-label';
            spanLabel.textContent = 'Trovato in:';
            successDiv.appendChild(spanLabel);

            const ulRes = document.createElement('ul');
            posizioni.forEach(pos => {
                const li = document.createElement('li');
                li.textContent = `Armadio ${pos.armadio}, Scaffale ${pos.scaffale}`;
                ulRes.appendChild(li);
            });

            successDiv.appendChild(ulRes);
            resultContainer.appendChild(successDiv);

        } catch (error) {
            resultContainer.replaceChildren();
            const errDiv = document.createElement('div');
            errDiv.className = 'loc-result error-loc';
            errDiv.textContent = 'Errore di localizzazione.';
            resultContainer.appendChild(errDiv);
        }
    }

    // init
    caricaArticoli();
});