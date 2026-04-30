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
    let tuttiGliArticoli = []; 

    const gestoreFiltri = new FilterManager(() => {
        const articoliFiltrati = gestoreFiltri.filtraArray(tuttiGliArticoli);
        mostraArticoli(articoliFiltrati);
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
            
            const option = document.createElement('option');
            option.value = ""; 
            option.textContent = "Seleziona tipologia...";
            selectTipologia.appendChild(option); //guarda se meglio rimetterlo o no

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
            grigliaArticoli.innerHTML = '<p class="loading-text">Caricamento modelli...</p>';

            const $service = 'articoli_service.php';
            const responseJSON = await ApiRequest.request($service, 'GET');
            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati articoli non validi");

            // prende dati in variabile
            tuttiGliArticoli = responseJSON.body;
            tuttiGliArticoli.sort((a, b) => a.nomeArticolo.localeCompare(b.nomeArticolo));

            gestoreFiltri.popolaFiltriBase(tuttiGliArticoli);
            
            const filtratiIniziali = gestoreFiltri.filtraArray(tuttiGliArticoli);
            mostraArticoli(filtratiIniziali);
        } 
        catch (e) { 
            console.log(e.message);
            grigliaArticoli.innerHTML = '<p class="error-text text-danger">Errore caricamento modelli.</p>'; 
        }
    }

    function mostraArticoli(artFiltrati){
        grigliaArticoli.replaceChildren();

        if (!artFiltrati || artFiltrati.length === 0) {
            const emptyMsg = document.createElement('p');
            emptyMsg.textContent = "Nessun modello trovato in catalogo.";
            grigliaArticoli.appendChild(emptyMsg);
            return;
        }

        artFiltrati.forEach(art => {
            const card = document.createElement('article');
            card.className = 'article-card';

            const h3 = document.createElement('h3'); 
            h3.textContent = art.nomeArticolo;
            const tag = document.createElement('span'); 
            tag.className = 'tag-tipologia'; 
            tag.textContent = art.nomeTipologia || "Sconosciuta";
            
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
            alert('Errore durante l\'eliminazione: ' + error.message);
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
            tipologiaId: parseInt(tipologiaId),
            nome: nomeArticolo,
            attributi: attributi
        };

        // gestione bottone di invio (disabilita e indica a utrnte)
        const btnSubmit = formCreaArticolo.querySelector('button[type="submit"]');
        const testoOriginale = btnSubmit.textContent;
        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Salvataggio in corso...';
        if(nomeArticolo == "" || tipologiaId <= 0){
            mostraFeedback('Inserisci dati validi: ', 'error');
            return;
        }
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
            attrCounter = 1; 

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