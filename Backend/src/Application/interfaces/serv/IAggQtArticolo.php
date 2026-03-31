<?php
declare(strict_types=1);
namespace Backend\Application\interfaces\serv;

interface IAggQtArticolo{
    public function execute(int $articoloId, int $numeroScaffale, int $armadioId, int $quantitaDaAggiungere):void;
}