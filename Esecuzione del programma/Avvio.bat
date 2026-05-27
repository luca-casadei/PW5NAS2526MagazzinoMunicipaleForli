@echo off
title Avvio Ambiente Docker

echo Avvio di Docker Desktop in corso...
:: Modifica il percorso se hai installato Docker in una cartella diversa
start "" "C:\Program Files\Docker\Docker\Docker Desktop.exe"

echo In attesa che il demone di Docker sia completamente operativo...
:wait_docker
:: Questo comando tenta di interrogare Docker. Se fallisce, aspetta 3 secondi e riprova.
docker info >nul 2>&1
if %errorlevel% neq 0 (
    timeout /t 5 /nobreak >nul
    goto wait_docker
)

echo Docker e pronto! Avvio dei container...
:: Imposta la directory di lavoro corrente su quella in cui si trova il file .bat
cd /d "%~dp0"
docker compose up -d

echo In attesa che i servizi interni si stabilizzino...
:: Attendi 5 secondi per dare tempo al web server interno di avviarsi (modifica il valore se necessario)
timeout /t 15 /nobreak >nul

echo Apertura di Google Chrome...
start chrome "http://localhost"

echo Operazione completata. La finestra si chiudera a breve.
timeout /t 3 >nul
exit