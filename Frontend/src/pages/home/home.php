<main class="home-dashboard">
    <header class="home-header">
        <h1>Dashboard Magazzino</h1>
        <p>Seleziona un'operazione per iniziare.</p>
    </header>

    <nav aria-label="Navigazione principale magazzino">
        <ul class="action-grid">
            <li class="action-card">
                <a href="/pages/gestisciArticoli/index_gestisciArticoli.php" class="action-link" aria-label="Vai alla pagina Gestisci Articoli">
                    <div class="icon-placeholder" aria-hidden="true">
                        <span class="icon">📦</span>
                    </div>
                    <h2>Gestisci Articoli</h2>
                    <p class="action-desc">
                        Vedi tutti gli articoli e la quantità in cui sono presenti. Possibilità di localizzarli, ovvero di vedere in quale armadio/i si trovano
                    </p>
                </a>
            </li>

            <li class="action-card">
                <a href="/pages/gestisciTipologie/index_gestisciTipologie.php" class="action-link" aria-label="Vai alla pagina Gestisci Tipologie">
                    <div class="icon-placeholder" aria-hidden="true">
                        <span class="icon">🏷️</span>
                    </div>
                    <h2>Gestisci Tipologie</h2>
                    <p class="action-desc">
                        Vedi tutte le tipologie degli articoli, aggiungi o elimina
                    </p>
                </a>
            </li>

            <li class="action-card">
                <a href="/pages/gestisciTipiArticoli/index_gestisciTipiArticoli.php" class="action-link" aria-label="Vai alla pagina Gestisci Tipi Articoli">
                    <div class="icon-placeholder" aria-hidden="true">
                        <span class="icon">⚙️</span>
                    </div>
                    <h2>Tipi Articoli</h2>
                    <p class="action-desc">
                        Vedi tutti i tipi di articoli con la possibilità di eliminarli o crearne di nuovi
                    </p>
                </a>
            </li>

            <li class="action-card">
                <a href="/pages/armadi/index_armadi.php" class="action-link" aria-label="Vai alla pagina Vedi Armadi">
                    <div class="icon-placeholder" aria-hidden="true">
                        <span class="icon">🗄️</span>
                    </div>
                    <h2>Vedi Armadi</h2>
                    <p class="action-desc">
                        Vedi tutti gli articoli suddivisi negli armadi e negli scaffali in cui si trovano
                    </p>
                </a>
            </li>
            <li class="action-card card-esporta">
                <a href="/services/esportaStorico_service.php" class="action-link" aria-label="scarica storico">
                    <div class="icon-placeholder" aria-hidden="true">
                        <span class="icon">📊</span>
                    </div>
                    <h2>Esporta Storico</h2>
                    <p class="action-desc">
                        Effettua l'esportazione dei dati sotto forma di file csv (relativi all'ultimo anno)
                    </p>
                </a>
            </li>
        </ul>
    </nav>
</main>