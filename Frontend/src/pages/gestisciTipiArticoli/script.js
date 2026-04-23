document.addEventListener('DOMContentLoaded', () => {
    const selectTipologia = document.getElementById('tipologia-selezionata');
    const containerAttributi = document.getElementById('contenitore-attributi');
    const btnAggiungiAttr = document.getElementById('btn-aggiungi-attributo');
    const grigliaArticoli = document.getElementById('griglia-articoli');
    const modal = document.getElementById('modal-delete');
    
    let idDaEliminare = null;
    let attrCounter = 0;

    // --- 1. CARICA TIPOLOGIE (Per la Select) ---
    async function caricaTipologie() {
        try {
            const tipologie = await new Promise(resolve => setTimeout(() => {
                resolve([{id: 1, nome: "Ferramenta"}, {id: 2, nome: "Abbigliamento"}, {id: 3, nome: "Elettronica"}]);
            }, 400));

            selectTipologia.replaceChildren();
            const def = document.createElement('option');
            def.value = ""; def.textContent = "Seleziona tipologia...";
            selectTipologia.appendChild(def);

            tipologie.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id; opt.textContent = t.nome;
                selectTipologia.appendChild(opt);
            });
        } catch (e) { console.error("Errore tipologie"); }
    }

    // --- 2. GESTIONE ATTRIBUTI DINAMICI NEL FORM ---
    btnAggiungiAttr.addEventListener('click', () => {
        attrCounter++;
        const row = document.createElement('div');
        row.className = 'attribute-row';

        const inNome = document.createElement('input');
        inNome.placeholder = "Nome (es. Peso)"; inNome.required = true;
        
        const inVal = document.createElement('input');
        inVal.placeholder = "Valore (es. 5kg)"; inVal.required = true;

        const btnRem = document.createElement('button');
        btnRem.type = "button"; btnRem.className = "btn-remove"; btnRem.textContent = "X";
        btnRem.onclick = () => row.remove();

        row.appendChild(inNome);
        row.appendChild(inVal);
        row.appendChild(btnRem);
        containerAttributi.appendChild(row);
    });

    // --- 3. CARICA E MOSTRA ARTICOLI (CON DOPPIO SORT) ---
    async function caricaArticoli() {
        try {
            const articoli = await new Promise(resolve => setTimeout(() => {
                resolve([
                    { id: 101, nome: "Trapano", tipologia: "Ferramenta", attributi: [{n: "Potenza", v: "750W"}, {n: "Alimentazione", v: "Cavo"}] },
                    { id: 102, nome: "Giacca", tipologia: "Abbigliamento", attributi: [{n: "Taglia", v: "XL"}, {n: "Colore", v: "Verde"}] }
                ]);
            }, 500));

            // Ordinamento 1: Articoli per nome
            articoli.sort((a, b) => a.nome.localeCompare(b.nome));

            grigliaArticoli.replaceChildren();

            articoli.forEach(art => {
                const card = document.createElement('article');
                card.className = 'article-card';

                const h3 = document.createElement('h3'); h3.textContent = art.nome;
                const tag = document.createElement('span'); tag.className = 'tag-tipologia'; tag.textContent = art.tipologia;
                
                const ul = document.createElement('ul');
                ul.className = 'attr-list';

                // Ordinamento 2: Attributi per nome
                art.attributi.sort((a, b) => a.n.localeCompare(b.n));

                art.attributi.forEach(attr => {
                    const li = document.createElement('li');
                    const s = document.createElement('strong'); s.textContent = `${attr.n}: `;
                    li.appendChild(s);
                    li.appendChild(document.createTextNode(attr.v));
                    ul.appendChild(li);
                });

                const btnDel = document.createElement('button');
                btnDel.className = "btn-danger"; btnDel.textContent = "Elimina Modello";
                btnDel.style.marginTop = "auto";
                btnDel.onclick = () => {
                    idDaEliminare = art.id;
                    document.getElementById('nome-art-delete').textContent = art.nome;
                    modal.showModal();
                };

                card.append(h3, tag, ul, btnDel);
                grigliaArticoli.appendChild(card);
            });
        } catch (e) { grigliaArticoli.textContent = "Errore caricamento."; }
    }

    // --- 4. GESTIONE MODALE ---
    document.getElementById('btn-cancel').onclick = () => modal.close();
    document.getElementById('btn-confirm').onclick = async () => {
        // Simulazione DELETE
        modal.close();
        console.log("Eliminato ID:", idDaEliminare);
        caricaArticoli(); // Refresh
    };

    // INIT
    caricaTipologie();
    caricaArticoli();
});