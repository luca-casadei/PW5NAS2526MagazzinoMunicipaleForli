document.addEventListener('DOMContentLoaded', () => {
    const contenitoreArmadi = document.getElementById('contenitore-armadi');

    // --- 1. SIMULAZIONE CHIAMATE API CONCATENATE ---

    async function caricaTuttoIlMagazzino() {
        try {
            // Chiamata 1: Ottieni Armadi
            const armadi = await getArmadi();
            contenitoreArmadi.replaceChildren();

            for (const armadio of armadi) {
                const armadioElement = creaElementoArmadio(armadio);
                contenitoreArmadi.appendChild(armadioElement);

                // Chiamata 2: Ottieni Scaffali per questo Armadio
                const scaffali = await getScaffali(armadio.id);
                const containerScaffali = armadioElement.querySelector('.scaffale-container');

                for (const scaffale of scaffali) {
                    const scaffaleElement = creaElementoScaffale(scaffale);
                    containerScaffali.appendChild(scaffaleElement);

                    // Chiamata 3: Ottieni Articoli per questo Scaffale
                    const articoli = await getArticoliScaffale(scaffale.id);
                    const gridArticoli = scaffaleElement.querySelector('.articoli-scaffale-grid');
                    
                    articoli.forEach(art => {
                        gridArticoli.appendChild(creaCardArticolo(art));
                    });
                }
            }
        } catch (error) {
            contenitoreArmadi.textContent = "Errore nel caricamento della struttura magazzino.";
        }
    }

    // --- 2. FUNZIONI DI CREAZIONE DOM (appendChild) ---

    function creaElementoArmadio(armadio) {
        const details = document.createElement('details');
        details.className = 'armadio-section';
        
        const summary = document.createElement('summary');
        summary.className = 'armadio-summary';
        summary.textContent = `Armadio Numero ${armadio.numero}`;
        
        const content = document.createElement('div');
        content.className = 'scaffale-container';
        
        details.append(summary, content);
        return details;
    }

    function creaElementoScaffale(scaffale) {
        const details = document.createElement('details');
        details.className = 'scaffale-section';
        
        const summary = document.createElement('summary');
        summary.className = 'scaffale-summary';
        summary.textContent = `Scaffale ${scaffale.numero}`;
        
        const grid = document.createElement('div');
        grid.className = 'articoli-scaffale-grid';
        
        details.append(summary, grid);
        return details;
    }

    function creaCardArticolo(art) {
        const card = document.createElement('article');
        card.className = 'articolo-mini-card';

        const h4 = document.createElement('h4');
        h4.textContent = art.nome;

        const infoQt = document.createElement('p');
        infoQt.innerHTML = `Qt: <span class="badge-qt" id="qt-val-${art.id}">${art.quantita}</span>`;

        const ul = document.createElement('ul');
        ul.className = 'attr-list';
        art.attributi.sort((a,b) => a.n.localeCompare(b.n)).forEach(attr => {
            const li = document.createElement('li');
            li.innerHTML = `<strong>${attr.n}:</strong> ${attr.v}`;
            ul.appendChild(li);
        });

        // Controlli Quantità
        const controlli = document.createElement('div');
        controlli.className = 'controlli-qt';

        const btnMinus = document.createElement('button');
        btnMinus.className = 'btn-qt-minus';
        btnMinus.textContent = '-1';
        btnMinus.onclick = () => aggiornaQuantita(art.id, -1);

        const inputAdd = document.createElement('input');
        inputAdd.type = 'number';
        inputAdd.className = 'input-add-qt';
        inputAdd.value = 1;
        inputAdd.min = 1;

        const btnAdd = document.createElement('button');
        btnAdd.className = 'btn-primary btn-qt-add';
        btnAdd.textContent = 'Aggiungi';
        btnAdd.onclick = () => {
            const val = parseInt(inputAdd.value);
            if(val > 0) aggiornaQuantita(art.id, val);
        };

        controlli.append(btnMinus, inputAdd, btnAdd);
        card.append(h4, infoQt, ul, controlli);
        return card;
    }

    // --- 3. LOGICA AZIONI ---

    async function aggiornaQuantita(id, delta) {
        // Qui andrebbe la chiamata fetch(POST /aggiornaQt)
        const span = document.getElementById(`qt-val-${id}`);
        let attuale = parseInt(span.textContent);
        let nuovo = attuale + delta;
        if (nuovo < 0) nuovo = 0;
        
        span.textContent = nuovo;
        // Feedback visivo temporaneo
        span.style.color = delta > 0 ? 'var(--success-color)' : 'var(--danger-color)';
        setTimeout(() => span.style.color = 'var(--primary-color)', 500);
    }

    // --- 4. SIMULATORI API ---

    function getArmadi() {
        return new Promise(res => setTimeout(() => res([{id:1, numero:10}, {id:2, numero:11}]), 400));
    }
    function getScaffali(id) {
        return new Promise(res => setTimeout(() => res([{id:10, numero:1}, {id:11, numero:2}]), 300));
    }
    function getArticoliScaffale(id) {
        return new Promise(res => setTimeout(() => res([
            {id: 50, nome: "Viti 4x20", quantita: 500, attributi: [{n:"Materiale", v:"Acciaio"}]},
            {id: 51, nome: "Bulloni M8", quantita: 120, attributi: [{n:"Tipo", v:"Esagonale"}]}
        ]), 300));
    }

    caricaTuttoIlMagazzino();
});