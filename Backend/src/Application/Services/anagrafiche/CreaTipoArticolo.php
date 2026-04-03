<?php
declare(strict_types=1);
namespace Backend\Application\Services;

use Backend\Application\interfaces\repo\ICreaTipologiaRepo;
use Backend\Application\interfaces\serv\ICreaTipoArticolo;
use Exception;


class CreaTipoArticolo implements ICreaTipoArticolo{
    private ICreaTipologiaRepo $repo;
    public function __construct(ICreaTipologiaRepo $repository){
        $this->repo = $repository;
    }
    public function execute(int $tipologiaId, array $attributi): void {
        
        if (empty($nomiAttributi)) {
            throw new Exception("Un Tipo Articolo deve avere almeno un attributo.");
        }

        $attributiIds = [];

        // 1. GESTIONE DIZIONARIO ATTRIBUTI
        foreach ($nomiAttributi as $nome) {
            // Cerchiamo se la parola esiste già
            $id = $this->attributiRepo->findIdByNome($nome);
            
            if ($id === null) {
                // Se non esiste, lo creiamo (come hai richiesto)
                $id = $this->attributiRepo->creaAttributo($nome);
            }
            
            $attributiIds[] = $id;
        }

        // Rimuoviamo eventuali duplicati per sicurezza
        $attributiIds = array_unique($attributiIds);

        // 2. CONTROLLO FIRMA ESATTA (La correzione logica)
        // Chiediamo al DB: "Esiste già un articolo della tipologia X che usa ESATTAMENTE questi ID?"
        $articoloEsistenteId = $this->articoloSchemaRepo->trovaArticoloPerFirmaEsatta($tipologiaId, $attributiIds);

        if ($articoloEsistenteId !== null) {
            // Se esiste, blocchiamo tutto.
            throw new Exception("Errore: Questo Tipo Articolo (Tipologia + Combinazione esatta di Attributi) esiste già.");
        }

        // 3. CREAZIONE DEL TIPO ARTICOLO
        // Creiamo un articolo "Template" (dovrai fornire codici fittizi o auto-generati per i campi NOT NULL)
        $nuovoArticoloId = $this->articoloSchemaRepo->creaArticoloTemplate(
            'TEMPLATE-' . time(), // Codice fittizio
            'TEMPLATE-INT',       // Codice fittizio
            $tipologiaId
        );

        // 4. ASSOCIAZIONE DEGLI ATTRIBUTI
        foreach ($attributiIds as $attrId) {
            // Inseriamo una stringa vuota o un placeholder poiché 'Valore' è NOT NULL nel tuo DB
            $this->articoloSchemaRepo->associaAttributo($nuovoArticoloId, $attrId, "DA_COMPILARE");
        }
    }
}


