import { ApiRequest } from "../../components/global/ApiRequest.js";

document.addEventListener('DOMContentLoaded', () => {
    const listaTipologie = document.getElementById('lista-tipologie');
    const formCreate = document.getElementById('form-crea-tipologia');
    const messageCreate = document.getElementById('msg-creazione');
    
    const dialog = document.getElementById('modal-eliminazione');
    const spanNomeDaEliminare = document.getElementById('nome-tipologia-da-eliminare');
    const btnAnnulla = document.getElementById('btn-annulla-elimina');
    const btnConfermaElimina = document.getElementById('btn-conferma-elimina');
    
    let nomeTipoDelete = null;
    // carica tipologie
    async function caricaTipologie() {
        try {
            const $servizio = 'tipologie_service.php';
            const responseJSON = await ApiRequest.request($servizio, 'GET');
            if(!responseJSON || !Array.isArray(responseJSON.body)) throw new Error("Dati tipologie non validi");
            const tipologie = responseJSON.body;
            // ordina alfabetico
            tipologie.sort((a, b) => a.nome.localeCompare(b.nome));

            listaTipologie.replaceChildren();
            if (tipologie.length === 0) {
                const emptyMsg = document.createElement('p');
                emptyMsg.textContent = "Nessuna tipologia presente.";
                listaTipologie.appendChild(emptyMsg);
                return;
            }

            // genera card tipologie
            tipologie.forEach(tipo => {
                const card = document.createElement('article');
                card.className = 'tipologia-card';

                const title = document.createElement('h3');
                title.textContent = tipo.nome;

                const btnElimina = document.createElement('button');
                btnElimina.className = 'btn-danger';
                btnElimina.textContent = 'Elimina';
                btnElimina.setAttribute('aria-label', `Elimina tipologia ${tipo.nome}`);
                
                btnElimina.addEventListener('click', () => apriDialogEliminazione(tipo.nome));

                card.appendChild(title);
                card.appendChild(btnElimina);
                listaTipologie.appendChild(card);
            });

        } catch (error) {
            listaTipologie.replaceChildren();
            const errMsg = document.createElement('p');
            errMsg.className = 'text-danger';
            errMsg.textContent = 'Errore nel caricamento delle tipologie.' + error.message;
            listaTipologie.appendChild(errMsg);
        }
    }

    // crea tipologia
    formCreate.addEventListener('submit', async (e) => {
        e.preventDefault();
        const inputNome = document.getElementById('nome-tipologia').value.trim();
        
        messageCreate.replaceChildren();
        messageCreate.className = 'msg-feedback'; // reset classi

        try {
            const $service = 'creaTipologia_service.php';
            const $responseJSON = await ApiRequest.request($service, 'POST', { nome: inputNome });
            if($responseJSON.status !== 'success') throw new Error($responseJSON.message || "Errore nella risposta del server");

            // Feedback visivo
            messageCreate.textContent = `Tipologia "${inputNome}" creata con successo!`;
            messageCreate.classList.add('msg-success');
            
            formCreate.reset(); // Svuota l'input
            caricaTipologie(); // Ricarica la lista aggiornata

        } catch (error) {
            messageCreate.textContent = `Errore durante la creazione della tipologia.`;
            messageCreate.classList.add('msg-error');
        }
    });

    // eliminazione
    function apriDialogEliminazione(nome) {
        nomeTipoDelete = nome; // Salviamo l'ID nascosto
        spanNomeDaEliminare.textContent = nome;
        dialog.showModal(); // API nativa per aprire il <dialog>
    }

    // Se annulla
    btnAnnulla.addEventListener('click', () => {
        dialog.close();
        nomeTipoDelete = null;
    });

    // se elimina
    btnConfermaElimina.addEventListener('click', async () => {
        if (!nomeTipoDelete) return;

        // Disabilitiamo il bottone per evitare doppi click
        btnConfermaElimina.disabled = true;
        btnConfermaElimina.textContent = 'Eliminazione...';

        try {
            const $service = 'eliminaTipologia_service.php';
            const $responseJSON = await ApiRequest.request($service, 'DELETE', { nome: nomeTipoDelete });
            if($responseJSON.status !== 'success') throw new Error($responseJSON.message || "Errore nella risposta del server");

            dialog.close();
            caricaTipologie(); // ricarica lista
        } 
        catch (error) {
            alert('Errore durante l\'eliminazione.');
        } 
        finally {
            // ripristino stato bottone
            btnConfermaElimina.disabled = false;
            btnConfermaElimina.textContent = 'Sì, Elimina Tutto';
            nomeTipoDelete = null;
        }
    });

    caricaTipologie();
});