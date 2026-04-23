document.addEventListener('DOMContentLoaded', () => {
    const listaTipologie = document.getElementById('lista-tipologie');
    const formCrea = document.getElementById('form-crea-tipologia');
    const msgCreazione = document.getElementById('msg-creazione');
    
    const modal = document.getElementById('modal-eliminazione');
    const spanNomeDaEliminare = document.getElementById('nome-tipologia-da-eliminare');
    const btnAnnulla = document.getElementById('btn-annulla-elimina');
    const btnConfermaElimina = document.getElementById('btn-conferma-elimina');
    
    let idTipologiaInEliminazione = null;

    // carica tipologie
    async function caricaTipologie() {
        try {
            // SIMULAZIONE: Sostituisci con fetch() verso GET /tipologie
            const tipologie = await new Promise(resolve => setTimeout(() => {
                resolve([
                    { id: 3, nome: 'Scarpe Antinfortunistiche' },
                    { id: 1, nome: 'Pantaloni' },
                    { id: 2, nome: 'T-Shirt' }
                ]);
            }, 500));

            // ordina alfabetico
            tipologie.sort((a, b) => a.nome.localeCompare(b.nome));

            listaTipologie.replaceChildren();

            if (tipologie.length === 0) {
                const emptyMsg = document.createElement('p');
                emptyMsg.textContent = "Nessuna tipologia presente.";
                listaTipologie.appendChild(emptyMsg);
                return;
            }

            // genera card
            tipologie.forEach(tipo => {
                const card = document.createElement('article');
                card.className = 'tipologia-card';

                const title = document.createElement('h3');
                title.textContent = tipo.nome;

                const btnElimina = document.createElement('button');
                btnElimina.className = 'btn-danger';
                btnElimina.textContent = 'Elimina';
                btnElimina.setAttribute('aria-label', `Elimina tipologia ${tipo.nome}`);
                
                btnElimina.addEventListener('click', () => apriModaleEliminazione(tipo.id, tipo.nome));

                card.appendChild(title);
                card.appendChild(btnElimina);
                listaTipologie.appendChild(card);
            });

        } catch (error) {
            listaTipologie.replaceChildren();
            const errMsg = document.createElement('p');
            errMsg.className = 'text-danger';
            errMsg.textContent = 'Errore nel caricamento delle tipologie.';
            listaTipologie.appendChild(errMsg);
        }
    }

    // crea tipologia
    formCrea.addEventListener('submit', async (e) => {
        e.preventDefault();
        const inputNome = document.getElementById('nome-tipologia').value.trim();
        
        msgCreazione.replaceChildren();
        msgCreazione.className = 'msg-feedback'; // reset classi

        try {
            // SIMULAZIONE: Sostituisci con fetch() verso POST /tipologie
            await new Promise((resolve, reject) => setTimeout(() => {
                // Simuliamo un successo
                resolve();
            }, 600));

            // Feedback visivo
            msgCreazione.textContent = `Tipologia "${inputNome}" creata con successo!`;
            msgCreazione.classList.add('msg-success');
            
            formCrea.reset(); // Svuota l'input
            caricaTipologie(); // Ricarica la lista aggiornata

        } catch (error) {
            msgCreazione.textContent = `Errore durante la creazione della tipologia.`;
            msgCreazione.classList.add('msg-error');
        }
    });

    // eliminazione
    function apriModaleEliminazione(id, nome) {
        idTipologiaInEliminazione = id; // Salviamo l'ID nascosto
        spanNomeDaEliminare.textContent = nome; // Mostriamo il nome per conferma
        modal.showModal(); // API nativa per aprire il <dialog>
    }

    // Se l'utente clicca "Annulla"
    btnAnnulla.addEventListener('click', () => {
        modal.close();
        idTipologiaInEliminazione = null;
    });

    // elimina tutto
    btnConfermaElimina.addEventListener('click', async () => {
        if (!idTipologiaInEliminazione) return;

        // Disabilitiamo il bottone per evitare doppi click
        btnConfermaElimina.disabled = true;
        btnConfermaElimina.textContent = 'Eliminazione...';

        try {
            // SIMULAZIONE: fetch verso DELETE /tipologie passando l'ID
            await new Promise(resolve => setTimeout(resolve, 800));

            modal.close();
            caricaTipologie(); // Ricarica la lista per far sparire la tipologia

        } catch (error) {
            alert('Errore durante l\'eliminazione.');
        } finally {
            // Ripristiniamo lo stato del bottone
            btnConfermaElimina.disabled = false;
            btnConfermaElimina.textContent = 'Sì, Elimina Tutto';
            idTipologiaInEliminazione = null;
        }
    });

    caricaTipologie();
});