document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTI DEL DOM ---
    const selectTipologia = document.getElementById('tipologia-selezionata');
    const containerAttributi = document.getElementById('contenitore-attributi');
    const btnAggiungiAttr = document.getElementById('btn-aggiungi-attributo');
    const grigliaArticoli = document.getElementById('griglia-articoli');

    // --- 1. SIMULAZIONE CHIAMATA: CARICA TIPOLOGIE ---
    async function caricaTipologie() {
        try {
            const tipologie = await new Promise(resolve => setTimeout(() => {
                resolve([
                    { id: 1, nome: 'Pantaloni' },
                    { id: 2, nome: 'T-Shirt' },
                    { id: 3, nome: 'Scarpe Antinfortunistiche' }
                ]);
            }, 500));

            // Svuota in modo sicuro
            selectTipologia.replaceChildren(); 
            
            // Opzione di default
            const defaultOpt = document.createElement('option');
            defaultOpt.value = "";
            defaultOpt.textContent = "Seleziona una tipologia...";
            selectTipologia.appendChild(defaultOpt);

            // Popola tipologie
            tipologie.forEach(tipo => {
                const opt = document.createElement('option');
                opt.value = tipo.id;
                opt.textContent = tipo.nome;
                selectTipologia.appendChild(opt);
            });
        } catch (error) {
            selectTipologia.replaceChildren();
            const errOpt = document.createElement('option');
            errOpt.value = "";
            errOpt.textContent = "Errore caricamento";
            selectTipologia.appendChild(errOpt);
        }
    }

    // --- 2. GESTIONE ATTRIBUTI DINAMICI (FORM) ---
    let attrCount = 0;
    btnAggiungiAttr.addEventListener('click', () => {
        attrCount++;
        const row = document.createElement('div');
        row.className = 'attribute-row';
        
        const inputNome = document.createElement('input');
        inputNome.type = 'text';
        inputNome.name = `attr_nome_${attrCount}`;
        inputNome.placeholder = 'Nome (es. Taglia)';
        inputNome.required = true;
        inputNome.setAttribute('aria-label', 'Nome attributo');

        const inputValore = document.createElement('input');
        inputValore.type = 'text';
        inputValore.name = `attr_valore_${attrCount}`;
        inputValore.placeholder = 'Valore (es. XL)';
        inputValore.required = true;
        inputValore.setAttribute('aria-label', 'Valore attributo');

        const btnRemove = document.createElement('button');
        btnRemove.type = 'button';
        btnRemove.className = 'btn-remove';
        btnRemove.setAttribute('aria-label', 'Rimuovi attributo');
        btnRemove.textContent = 'X';
        
        // Gestione rimozione riga senza dover ri-cercare l'elemento nel DOM
        btnRemove.addEventListener('click', () => row.remove());
        
        row.appendChild(inputNome);
        row.appendChild(inputValore);
        row.appendChild(btnRemove);
        
        containerAttributi.appendChild(row);
    });

    // --- 3. SIMULAZIONE CHIAMATA: CARICA ARTICOLI ---
    async function caricaArticoli() {
        try {
            const articoli = await new Promise(resolve => setTimeout(() => {
                resolve([
                    {
                        id: 1,
                        nome: "Pantaloni da Lavoro",
                        tipologia: "Pantaloni",
                        quantitaTotale: 45,
                        attributi: [{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" }]
                    },
                    {
                        id: 2,
                        nome: "Maglietta Estiva",
                        tipologia: "T-Shirt",
                        quantitaTotale: 0,
                        attributi: [{ nome: "Taglia", valore: "M" }]
                    },
                    {
                        id: 1,
                        nome: "Pantaloni da Lavoro",
                        tipologia: "Pantaloni",
                        quantitaTotale: 45,
                        attributi: [{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" },{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" },{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" },{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" }]
                    },
                    {
                        id: 2,
                        nome: "Maglietta Estiva",
                        tipologia: "T-Shirt",
                        quantitaTotale: 0,
                        attributi: [{ nome: "Taglia", valore: "M" }]
                    },
                    {
                        id: 1,
                        nome: "Pantaloni da Lavoro",
                        tipologia: "Pantaloni",
                        quantitaTotale: 45,
                        attributi: [{ nome: "Taglia", valore: "L" }, { nome: "Colore", valore: "Blu" }]
                    },
                    {
                        id: 2,
                        nome: "Maglietta Estiva",
                        tipologia: "T-Shirt",
                        quantitaTotale: 0,
                        attributi: [{ nome: "Taglia", valore: "M" }]
                    }
                ]);
            }, 600));

            grigliaArticoli.replaceChildren(); // Rimuovi il testo "Caricamento..."

            articoli.sort((a, b) => a.nome.localeCompare(b.nome));
            
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

                if (art.attributi && art.attributi.length > 0) {
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
                // Associo l'evento direttamente qui, molto più performante!
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
            errP.className = 'error-text';
            errP.textContent = 'Errore nel caricamento degli articoli.';
            grigliaArticoli.appendChild(errP);
        }
    }

    // --- 4. SIMULAZIONE CHIAMATA: LOCALIZZA ARTICOLO ---
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
                } else {
                    resolve([]); 
                }
            }, 800));

            resultContainer.replaceChildren(); // Rimuoviamo il loading

            if (posizioni.length === 0) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'loc-result error-loc'; // Usiamo la classe CSS qui
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
            errDiv.className = 'loc-result error-loc'; // Usiamo la classe CSS qui
            errDiv.textContent = 'Errore di localizzazione.';
            resultContainer.appendChild(errDiv);
        }
    }

    // --- INIT ---
    caricaTipologie();
    caricaArticoli();
});