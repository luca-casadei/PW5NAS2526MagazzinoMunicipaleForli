import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

//RICORDATI DI DIRE A CLIENTE DI SVUOTARE A MANO IL FILTRO SE RIVUOLE VECCHI ELEMENTI NEL FILTRO
document.addEventListener('DOMContentLoaded', () => {
    const gridArticoli = document.getElementById('griglia-articoli');
    const chkbSoglia = document.getElementById('chk-soglia');
    const valSoglia = document.getElementById('val-soglia');
    
    let tuttiGliArticoli = []; // articoli generici

    // filtri
    const gestoreFiltri = new FilterManager((valoriAttuali) => {
        applicaFiltri(valoriAttuali);
    });

    // soglia quantita minima
    chkbSoglia.addEventListener('change', () => {
        valSoglia.disabled = !chkbSoglia.checked;
        caricaArticoli(); 
    });

    valSoglia.addEventListener('change', () => {
        if (chkbSoglia.checked) caricaArticoli();
    });

    // carica articoli
    async function caricaArticoli() {
        try {
            // caricamento visivo
            gridArticoli.replaceChildren(); 
            const pLoading = document.createElement('p');
            pLoading.className = 'loading-text';
            pLoading.textContent = 'Caricamento articoli in corso...';
            gridArticoli.appendChild(pLoading);

            // scarica articoli
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

            // prende dati in variabile
            tuttiGliArticoli = responseJSON.body;
            
            // cambia filtri
            popolaFiltri(tuttiGliArticoli);
            
            // Applichiamo eventuali filtri già scritti dall'utente e disegniamo l'HTML
            applicaFiltri(gestoreFiltri.getValues());

        } catch (error) {
            gridArticoli.replaceChildren();
            const errP = document.createElement('p');
            errP.className = 'error-text text-danger';
            errP.textContent = 'Errore nel caricamento degli articoli.';
            gridArticoli.appendChild(errP);
            console.error(error);
        }
    }

    // filtri in locale
    function applicaFiltri(filtri) {
        if (!tuttiGliArticoli || tuttiGliArticoli.length === 0) {
            mostraArticoli([]); 
            return;
        }

        const articoliFiltrati = tuttiGliArticoli.filter(art => {
            // Assicuriamoci che i campi esistano prima di fare toLowerCase() per evitare errori
            const nomeArt = art.nomeArticolo || "";
            const nomeTip = art.nomeTipologia || "";

            // filtro nome art
            const matchNome = filtri.nome === '' || 
                              nomeArt.toLowerCase().includes(filtri.nome.toLowerCase());
            
            // filtro tipologia (usiamo direttamente art.nomeTipologia!)
            const matchTipologia = filtri.tipologia === '' || 
                                   nomeTip.toLowerCase().includes(filtri.tipologia.toLowerCase());
            
            // filtro attr
            let matchAttributi = true; 
            if (filtri.attributoNome !== '') {
                const attrTrovato = art.attributi && art.attributi.find(a => 
                    a.nome.toLowerCase().includes(filtri.attributoNome.toLowerCase())
                );
                
                if (!attrTrovato) {
                    matchAttributi = false; // Non ha l'attributo
                } else if (filtri.attributoValore !== '') {
                    const matchValore = attrTrovato.valore.toLowerCase().includes(filtri.attributoValore.toLowerCase());
                    if (!matchValore) matchAttributi = false; // Il valore non corrisponde
                }
            }

            return matchNome && matchTipologia && matchAttributi;
        });

        // genera html
        mostraArticoli(articoliFiltrati);
    }

    // popola filtri
    function popolaFiltri(articoli) {
        const setNomi = new Set();
        const setTipologie = new Set();
        const setAttrNomi = new Set();
        const setAttrValori = new Set();

        articoli.forEach(art => {
            if (art.nomeArticolo) setNomi.add(art.nomeArticolo);
            
            // Usiamo direttamente il nome della tipologia dall'articolo
            if (art.nomeTipologia) setTipologie.add(art.nomeTipologia);
            
            if (art.attributi && Array.isArray(art.attributi)) {
                art.attributi.forEach(attr => {
                    if (attr.nome) setAttrNomi.add(attr.nome);
                    if (attr.valore) setAttrValori.add(attr.valore);
                });
            }
        });

        const riempiDatalist = (idDatalist, setDati) => {
            const datalist = document.getElementById(idDatalist);
            if (!datalist) return;
            datalist.replaceChildren(); 
            
            Array.from(setDati).sort().forEach(valore => {
                const opt = document.createElement('option');
                opt.value = valore;
                datalist.appendChild(opt);
            });
        };

        riempiDatalist('lista-nomi', setNomi);
        riempiDatalist('lista-tipologie', setTipologie);
        riempiDatalist('lista-attributi', setAttrNomi);
        riempiDatalist('lista-valori', setAttrValori);
    }

    // fa html
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
            // Inseriamo direttamente la stringa dal backend
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

            card.appendChild(title);
            card.appendChild(tag);
            card.appendChild(qt);
            card.appendChild(ul);
            card.appendChild(btnLocalizza);
            card.appendChild(resultContainer);

            gridArticoli.appendChild(card);
        });
    }

    // localizza
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

    // avvia pagina
    caricaArticoli();
});