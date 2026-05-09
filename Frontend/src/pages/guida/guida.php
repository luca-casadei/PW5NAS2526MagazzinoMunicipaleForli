<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>
<main class="guida-dashboard">
    <header class="guida-header">
        <h1>Guida al Magazzino</h1>
        <p>Scopri come utilizzare al meglio tutte le funzionalità del sistema.</p>
    </header>

    <section aria-labelledby="titolo-tipologie" class="guida-section">
        <h2 id="titolo-tipologie"><span aria-hidden="true">🏷️</span> Gestisci Tipologie</h2>
        <p>Le Tipologie sono le macro-categorie degli articoli (es. <em>Vestiario, Dispositivi</em>), a cui verranno associati gli articoli. In questa pagina puoi:</p>
        <ul>
            <li>Vedere tutte le tipologie già esistenti.</li>
            <li>Creare una nuova tipologia inserendo il nome.</li>
            <li>Eliminare una tipologia esistente. <br> <strong>ATTENZIONE!:</strong> se elimini una tipologia verranno eliminati definitivamente anche tutti gli articoli di quella tipologia.</li>
        </ul>
    </section>

    <section aria-labelledby="titolo-configurazione" class="guida-section">
        <h2 id="titolo-configurazione"><span aria-hidden="true">⚙️</span> Tipi Articoli (Configurazione)</h2>
        <p>Questa pagina serve per creare i modelli base degli articoli. In questa pagina puoi:</p>
        <ul>
            <li>Creare un nuovo modello di articolo:
                <ul>
                    <li>Inserisci il nome dell'articolo e seleziona la tipologia a cui appartiene.</li>
                    <li>Inserisci tutte le caratteristiche che ha: almeno una (obbligatoria) e con la possibilità di aggiungere tutti quelli necessari, inserendo il nome della caratteristica e il valore correlato.</li>
                    <li>I modelli non possono essere identici per nome, tipologia e lista di caratteristiche con valore. Perciò bisogna creare un modello diverso ogni qualvolta ci sia un articolo con caratteristiche o valori diversi. Per esempio bisogna creare due modelli diversi se due articoli hanno come caratteristica Colore ma uno è Verde mentre l'altro Rosso.</li>
                </ul>
            </li>
            <li>Vedere tutti i modelli già esistenti.</li>
            <li>Filtrare sui modelli esistenti per trovarli più facilmente.</li>
            <li>Eliminare un modello esistente. <br><strong>ATTENZIONE!:</strong> verranno eliminati tutti gli articoli e le quantità correlate dal magazzino.</li>
        </ul>
    </section>

    <section aria-labelledby="titolo-articoli" class="guida-section">
        <h2 id="titolo-articoli"><span aria-hidden="true">📦</span> Gestisci Articoli</h2>
        <p>Questa è la sezione principale per monitorare le giacenze del magazzino. In questa pagina puoi:</p>
        <ul>
            <li>Vedere tutti gli articoli e la quantità totale in cui sono presenti fisicamente nel magazzino. <em>Nota a margine:</em> Se la quantità è 0 significa che esiste il modello ma non è presente in magazzino.</li>
            <li>Filtrare sugli articoli presenti per trovarli più facilmente.</li>
            <li>Vedere solo gli articoli la cui quantità è sotto una certa soglia che si può modificare tramite casella se si ha cliccato l'opzione.</li>
            <li>Trovare in quale armadi e scaffali si trova un articolo cliccando su Localizza. Se la quantità è 0, come detto, verrà indicato che non è presente in nessun armadio.</li>
            <li>Aggiungere un articolo in un armadio e uno scaffale scegliendo la quantità, cliccando su Aggiungi.</li>
            <li>Creare un nuovo modello di articolo cliccando sul pulsante Crea Modello che ti porta alla pagina Tipi Articoli.</li>
        </ul>
    </section>

    <section aria-labelledby="titolo-armadi" class="guida-section">
        <h2 id="titolo-armadi"><span aria-hidden="true">🗄️</span> Vedi Armadi</h2>
        <p>Questa è la mappa fisica del tuo magazzino. In questa pagina puoi:</p>
        <ul>
            <li>Vedere il magazzino suddiviso per armadi i quali sono suddivisi in scaffali.</li>
            <li>Creare un nuovo armadio tramite pulsante.</li>
            <li>Creare un nuovo scaffale in un armadio tramite pulsante.</li>
            <li>Eliminare un armadio tramite pulsante (cestino rosso). <br><strong>ATTENZIONE!:</strong> l'eliminazione è irreversibile, perciò prima bisognerà eliminare tutti gli scaffali al suo interno (se presenti).</li>
            <li>Eliminare un scaffale tramite pulsante (cestino rosso).<br> <strong>ATTENZIONE!:</strong> l'eliminazione è irreversibile, perciò prima bisognerà eliminare tutti gli articoli al suo interno (se presenti), diminuendone la quantità nuova e usata fino a 0.</li>
            <li>Aprire e chiudere la sezione di armadi o scaffali cliccandoci sopra, per facilitare la visione.</li>
            <li>Vedere gli articoli presenti in ogni scaffale e la quantità in cui sono presenti.</li>
            <li>Aggiungere o rimuovere una quantità inserita in una stessa cella da un articolo. Se clicchi Rimuovi allora la quantità verrà tolta, altrimenti verrà aggiunta.</li>
            <li>Aggiungere un nuovo articolo allo scaffale tramite pulsante, che ti porta alla pagina di Gestisci Articoli.</li>
        </ul>
    </section>

    <section aria-labelledby="titolo-esporta" class="guida-section">
        <h2 id="titolo-esporta"><span aria-hidden="true">📊</span> Esporta Storico</h2>
        <p>Cliccando su questa opzione, il sistema genererà automaticamente un file .csv e lo scaricherà sul tuo computer.</p>
        <ul>
            <li>Il file conterrà tutto lo storico dei movimenti di magazzino dell'ultimo anno.</li>
            <li>Può essere aperto comodamente con Microsoft Excel, Google Sheets o Apple Numbers per effettuare conteggi, statistiche o inventari.</li>
        </ul>
    </section>
</main>