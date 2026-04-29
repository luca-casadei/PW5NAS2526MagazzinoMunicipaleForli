<?php
    $root_dir = $root_path->get_root_dir();
    require $root_dir . "/components/global/header.php";
?>
<main class="guida-dashboard">
    <header class="guida-header">
        <h1>Guida al Magazzino</h1>
        <p>Scopri come utilizzare al meglio tutte le funzionalità del sistema.</p>
    </header>

    <section class="guida-section">
        <h2>📦 Gestisci Articoli</h2>
        <p>Questa è la sezione principale per monitorare le giacenze del magazzino. Qui puoi:</p>
        <ul>
            <li>Vedere la <strong>quantità totale</strong> di ogni articolo presente.</li>
            <li>Usare il filtro in alto per cercare per Nome, Tipologia o caratteristiche specifiche (es. <span class="guida-highlight">Colore: Rosso</span>).</li>
            <li>Abilitare il filtro "Mostra articoli sotto soglia" per individuare velocemente le scorte in esaurimento.</li>
            <li>Cliccare su <strong>📍 Localizza</strong> per scoprire l'esatta posizione dell'articolo (Armadio e Scaffale).</li>
            <li>Cliccare su <strong>➕ Aggiungi</strong> per posizionare nuove unità dell'articolo in uno scaffale.</li>
        </ul>
    </section>

    <section class="guida-section">
        <h2>🏷️ Gestisci Tipologie</h2>
        <p>Le Tipologie sono le macro-categorie degli articoli (es. <em>Maglie, Pantaloni, Utensili</em>).</p>
        <ul>
            <li>Da questa pagina puoi creare nuove categorie scrivendo il nome e cliccando "Crea Nuova".</li>
            <li>Puoi eliminare le tipologie esistenti tramite l'apposito tasto rosso.</li>
        </ul>
    </section>

    <section class="guida-section">
        <h2>⚙️ Tipi Articoli (Configurazione)</h2>
        <p>Prima di poter mettere un articolo su uno scaffale, devi creare il suo "Modello Base" in questa sezione.</p>
        <ul>
            <li>Compila i <strong>Dati Obbligatori</strong> (Nome e Tipologia).</li>
            <li>Aggiungi tutte le caratteristiche che distinguono questo modello (es. Peso, Taglia, Materiale). <em>Attenzione:</em> almeno una caratteristica è sempre obbligatoria.</li>
            <li>Una volta salvato, il modello apparirà nel catalogo di destra e sarà pronto per essere inserito fisicamente negli armadi.</li>
        </ul>
    </section>

    <section class="guida-section">
        <h2>🗄️ Vedi Armadi</h2>
        <p>Questa è la mappa fisica del tuo magazzino.</p>
        <ul>
            <li>Il magazzino è suddiviso gerarchicamente in <strong>Armadi</strong>. Ogni armadio contiene più <strong>Scaffali</strong>.</li>
            <li>Puoi creare nuovi Armadi e aggiungere Scaffali al loro interno cliccando sugli appositi pulsanti in alto o a fine riga.</li>
            <li>All'interno di ogni Scaffale vedrai la lista degli articoli presenti.</li>
            <li>Puoi modificare rapidamente le giacenze premendo i pulsanti <strong>-1</strong> oppure inserendo un numero e premendo <strong>Aggiungi</strong> per aumentare le scorte fisiche in quello specifico punto.</li>
        </ul>
    </section>

    <section class="guida-section">
        <h2>📊 Esporta Storico</h2>
        <p>Cliccando su questa opzione, il sistema genererà automaticamente un file <strong>.csv</strong> scaricabile sul tuo computer.</p>
        <ul>
            <li>Il file conterrà tutto lo storico dei movimenti di magazzino dell'ultimo anno.</li>
            <li>Può essere aperto comodamente con Microsoft Excel, Google Sheets o Apple Numbers per effettuare conteggi, statistiche o inventari.</li>
        </ul>
    </section>
</main>