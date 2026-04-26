import { ApiRequest } from "../../components/global/ApiRequest.js";
import { FilterManager } from "../../components/global/filters-manager.js";

document.addEventListener('DOMContentLoaded', () => {
    const selectTipologia = document.getElementById('tipologia-selezionata');
    const containerAttributi = document.getElementById('contenitore-attributi');
    const btnAggiungiAttr = document.getElementById('btn-aggiungi-attributo');
    const grigliaArticoli = document.getElementById('griglia-articoli');
    const modal = document.getElementById('modal-delete');
    const btnConfirm = document.getElementById('btn-confirm');
    const formCreaArticolo = document.getElementById('form-crea-articolo');
    const msgFeedback = document.getElementById('msg-feedback');
    
    let idDaEliminare = null;
    let attrCounter = 1;
    let articoli = [];

    const gestoreFiltri = new FilterManager((valoriAttuali) => {
        applicaFiltri(valoriAttuali);
    });

    // tipologie per sleect
    async function caricaTipologie() {
        try {
            const $servizio = 'tipologie_service.php';
            const responseJSON = await ApiRequest.request($servizio, 'GET');
            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati tipologie non validi");
            const tipologie = responseJSON.body;
            // ordina alfabetico
            tipologie.sort((a, b) => a.nome.localeCompare(b.nome));

            selectTipologia.replaceChildren();
            /*const option = document.createElement('option');
            option.value = ""; 
            option.textContent = "Seleziona tipologia...";
            selectTipologia.appendChild(option);*/ //guarda se meglio rimetterlo o no

            tipologie.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id; opt.textContent = t.nome;
                selectTipologia.appendChild(opt);
            });
        } catch (e) { console.error("Errore tipologie:" + e.message); }
    }

    // attr dinamici da aggiungere
    btnAggiungiAttr.addEventListener('click', () => {
        attrCounter++;
        const row = document.createElement('div');
        row.className = 'attribute-row';

        const inNome = document.createElement('input');
        inNome.placeholder = "Nome (es. Peso)"; 
        inNome.required = true;
        
        const inVal = document.createElement('input');
        inVal.placeholder = "Valore (es. 5kg)"; 
        inVal.required = true;

        const btnRem = document.createElement('button');
        btnRem.type = "button"; 
        btnRem.className = "btn-remove"; 
        btnRem.textContent = "X";
        btnRem.onclick = () => row.remove();

        row.appendChild(inNome);
        row.appendChild(inVal);
        row.appendChild(btnRem);
        containerAttributi.appendChild(row);
    });

    // carica e mostra articoli
    async function caricaArticoli() {
        try {
            const $service = 'articoli_service.php';
            const responseJSON = await ApiRequest.request($service, 'GET');
            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati articoli non validi");

            // prende dati in variabile
            articoli = responseJSON.body;

            popolaFiltri(articoli);
            applicaFiltri(gestoreFiltri.getValues());
            articoli.sort((a, b) => a.nomeArticolo.localeCompare(b.nomeArticolo));
        } 
        catch (e) { 
            console.log(e.message);
            grigliaArticoli.textContent = "Errore caricamento."; 
        }
    }
    function applicaFiltri(filtri) {
        if (!articoli || articoli.length === 0) {
            mostraArticoli([]); 
            return;
        }

        const articoliFiltrati = articoli.filter(art => {
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

    function popolaFiltri(articols) {
        const setNomi = new Set();
        const setTipologie = new Set();
        const setAttrNomi = new Set();
        const setAttrValori = new Set();

        articols.forEach(art => {
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

    function mostraArticoli(artFiltrati){
        grigliaArticoli.replaceChildren();
        artFiltrati.forEach(art => {
            const card = document.createElement('article');
            card.className = 'article-card';

            const h3 = document.createElement('h3'); 
            h3.textContent = art.nomeArticolo;
            const tag = document.createElement('span'); 
            tag.className = 'tag-tipologia'; 
            tag.textContent = art.nomeTipologia;
            
            const ul = document.createElement('ul');
            ul.className = 'attr-list';

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

            const btnDel = document.createElement('button');
            btnDel.className = "btn-danger"; 
            btnDel.textContent = "Elimina Modello";
            btnDel.style.marginTop = "auto";
            btnDel.onclick = () => {
                idDaEliminare = art.idArticolo;
                //btnDel.disabled = true;
                document.getElementById('nome-art-delete').textContent = art.nomeArticolo;
                modal.showModal();
            };
            card.append(h3, tag, ul, btnDel);
            grigliaArticoli.appendChild(card);
        });
    }

    // dialog
    document.getElementById('btn-cancel').onclick = () => {
        modal.close();
        idDaEliminare = null;
    } 
    btnConfirm.onclick = async () => {
        if (!idDaEliminare) return; 

        btnConfirm.disabled = true;
        const testoOriginale = btnConfirm.textContent; 
        btnConfirm.textContent = 'Eliminazione in corso...';

        try {
            const $service = 'eliminaArticolo_service.php';
            const $responseJSON = await ApiRequest.request($service, 'DELETE', { articoloId: idDaEliminare });
            if($responseJSON.status !== 'success') throw new Error($responseJSON.message || "Errore nella risposta del server");

            modal.close();
            idDaEliminare = null;
            caricaArticoli(); 
        } 
        catch (error) {
            alert('Errore durante l\'eliminazione: ');
        } 
        finally {
            btnConfirm.disabled = false;
            btnConfirm.textContent = testoOriginale;
        }
    };

    formCreaArticolo.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nomeArticolo = document.getElementById('nome-articolo').value.trim();
        const tipologiaId = document.getElementById('tipologia-selezionata').value;

        const attributi = [];
        const righeAttributi = containerAttributi.querySelectorAll('div');
        
        righeAttributi.forEach(riga => {
            const inputs = riga.querySelectorAll('input');
            if (inputs.length >= 2) {
                const nomeAttr = inputs[0].value.trim();
                const valAttr = inputs[1].value.trim();
                
                // aggiungo solo se valorizzati
                if (nomeAttr !== '' && valAttr !== '') {
                    attributi.push({
                        nome: nomeAttr,
                        valore: valAttr
                    });
                }
            }
        });

        const payload = {
            tipologiaId: parseInt(tipologiaId), // Convertiamo in numero per il database
            nome: nomeArticolo,
            attributi: attributi
        };

        // gestione bottone di invio (disabilita e indica a utrnte)
        const btnSubmit = formCreaArticolo.querySelector('button[type="submit"]');
        const testoOriginale = btnSubmit.textContent;
        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Salvataggio in corso...';

        try {
            const responseJSON = await ApiRequest.request('salvaArticolo_service.php', 'POST', payload);
            if(responseJSON.status !== 'success') {
                throw new Error(responseJSON.message || "Errore dal server.");
            }
            mostraFeedback('Modello creato con successo!', 'success');
            formCreaArticolo.reset(); 
            
            // rimuovo attributi aggiunti
            const righeAggiuntive = containerAttributi.querySelectorAll('.attribute-row');
            righeAggiuntive.forEach(riga => riga.remove());
            attrCounter = 1; // Resetto il contatore

            caricaArticoli();

        } catch (error) {
            mostraFeedback('Errore durante il salvataggio: ' + error.message, 'error');
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.textContent = testoOriginale;
        }
    });

    function mostraFeedback(messaggio, tipo) {
        msgFeedback.className = 'msg-feedback';
        const prefisso = tipo === 'success' ? '✅ Successo: ' : '❌ Errore: ';
        msgFeedback.textContent = prefisso + messaggio;
        const classeTipo = tipo === 'success' ? 'msg-success' : 'msg-error';
        msgFeedback.classList.add(classeTipo, 'show');
        
        const tempoChiusura = tipo === 'success' ? 4000 : 8000;
        setTimeout(() => { 
            msgFeedback.classList.remove('show'); 
            msgFeedback.textContent = ''; 
        }, tempoChiusura);
    }

    caricaTipologie();
    caricaArticoli();
    //btnAggiungiAttr.click(); //meglio emtterlo obbligatorio se no può toglierlo
});