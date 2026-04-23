document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTI DEL DOM ---
    const grigliaArticoli = document.getElementById('griglia-articoli');

    // --- 1. SIMULAZIONE CHIAMATA: CARICA ARTICOLI ---
    async function caricaArticoli() {
        try {
            // SIMULAZIONE: Sostituisci con fetch() verso il tuo backend
            const articoli = await new Promise(resolve => setTimeout(() => {
                resolve([
                    {
                        id: 1,
                        nome: "Pantaloni da Lavoro",
                        tipologia: "Pantaloni",
                        quantitaTotale: 45,
                        attributi: [
                            { nome: "Colore", valore: "Blu" },
                            { nome: "Taglia", valore: "L" },
                            { nome: "Tessuto", valore: "Cotone" }
                        ]
                    },
                    {
                        id: 2,
                        nome: "Maglietta Estiva",
                        tipologia: "T-Shirt",
                        quantitaTotale: 0,
                        attributi: [{ nome: "Taglia", valore: "M" }]
                    },
                    {
                        id: 3,
                        nome: "Scarpe Antinfortunistiche",
                        tipologia: "Calzature",
                        quantitaTotale: 12,
                        attributi: [
                            { nome: "Materiale", valore: "Pelle" },
                            { nome: "Taglia", valore: "42" },
                            { nome: "Certificazione", valore: "S3" }
                        ]
                    }
                ]);
            }, 600));

            // --- ORDINAMENTO 1: Articoli per Nome ---
            articoli.sort((a, b) => a.nome.localeCompare(b.nome));

            grigliaArticoli.replaceChildren(); // Rimuovi il testo "Caricamento..."

            articoli.forEach(art => {
                const card = document.createElement('article');
                card.className = 'article-card';
                
                const title = document.createElement('h3');
                title.textContent = art.nome;

                const tag = document.createElement('span');
                tag.className = 'tag-tipologia';
                tag.textContent = art.tipologia;

                const qt = document.createElement('p');
                qt.className = 'qt-badge';
                qt.textContent = `Quantità in magazzino: ${art.quantitaTotale}`;

                const ul = document.createElement('ul');
                ul.className = 'attr-list';

                // --- ORDINAMENTO 2: Attributi per Nome (Dinamico per ogni articolo) ---
                if (art.attributi && art.attributi.length > 0) {
                    
                    // Ordiniamo l'array degli attributi prima di creare gli elementi <li>
                    art.attributi.sort((a, b) => a.nome.localeCompare(b.nome));

                    art.attributi.forEach(attr => {
                        const li = document.createElement('li');
                        const strong = document.createElement('strong');
                        strong.textContent = `${attr.nome}: `;
                        li.appendChild(strong);
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
                btnLocalizza.addEventListener('click', () => eseguiLocalizzazione(art.id));

                const resultContainer = document.createElement('div');
                resultContainer.className = 'loc-result-container';
                resultContainer.id = `loc-res-${art.id}`;

                // Assembliamo la card
                card.appendChild(title);
                card.appendChild(tag);
                card.appendChild(qt);
                card.appendChild(ul);
                card.appendChild(btnLocalizza);
                card.appendChild(resultContainer);

                grigliaArticoli.appendChild(card);
            });

        } catch (error) {
            grigliaArticoli.replaceChildren();
            const errP = document.createElement('p');
            errP.className = 'error-text text-danger';
            errP.textContent = 'Errore nel caricamento degli articoli.';
            grigliaArticoli.appendChild(errP);
        }
    }

    // --- 2. SIMULAZIONE CHIAMATA: LOCALIZZA ARTICOLO (Invariata) ---
    async function eseguiLocalizzazione(articoloId) {
        const resultContainer = document.getElementById(`loc-res-${articoloId}`);
        resultContainer.replaceChildren(); 
        
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'loc-result';
        loadingDiv.textContent = 'Ricerca in corso...';
        resultContainer.appendChild(loadingDiv);

        try {
            const posizioni = await new Promise(resolve => setTimeout(() => {
                if (articoloId === 1) {
                    resolve([{ armadio: 1, scaffale: 2 }, { armadio: 3, scaffale: 1 }]);
                } else if (articoloId === 3) {
                    resolve([{ armadio: 2, scaffale: 4 }]);
                } else {
                    resolve([]); 
                }
            }, 800));

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
            
            const strong = document.createElement('strong');
            strong.textContent = 'Trovato in:';
            successDiv.appendChild(strong);

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

    // --- INIT ---
    caricaArticoli();
});