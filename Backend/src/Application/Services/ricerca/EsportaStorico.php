<?php
declare(strict_types=1);

namespace Backend\Application\Services\ricerca;

use Backend\Application\interfaces\repo\IEsportaStoricoRepo;
use Backend\Application\interfaces\serv\IEsportaStorico;

class EsportaStorico implements IEsportaStorico {
    private IEsportaStoricoRepo $storicoRepo;

    public function __construct(IEsportaStoricoRepo $storicoRepo) {
        $this->storicoRepo = $storicoRepo;
    }
    public function execute(): array {
        // Logica di business: calcoliamo il range di date per "l'ultimo anno"
        // Formato compatibile con il DATETIME di MySQL (YYYY-MM-DD HH:MM:SS)
        $dataFine = date('Y-m-d H:i:s'); // Adesso
        $dataInizio = date('Y-m-d H:i:s', strtotime('-1 year')); // Esattamente un anno fa

        // Chiamiamo il repository passandogli i filtri temporali
        return $this->storicoRepo->getMovimentiByDateRange($dataInizio, $dataFine);
    }
}