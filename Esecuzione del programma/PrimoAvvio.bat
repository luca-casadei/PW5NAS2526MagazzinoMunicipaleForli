@echo off
title Avvio e Rebuild Ambiente Docker

echo Avvio di Docker Desktop in corso...
start "" "C:\Program Files\Docker\Docker\Docker Desktop.exe"

echo In attesa che il demone di Docker sia completamente operativo...
:wait_docker
docker info >nul 2>&1
if %errorlevel% neq 0 (
    timeout /t 5 /nobreak >nul
    goto wait_docker
)

echo Docker e pronto! Ricostruzione e avvio dei container...
cd /d "%~dp0"
docker compose up --build -d

echo In attesa che i servizi interni si stabilizzino...
timeout /t 20 /nobreak >nul

echo Apertura di Google Chrome...
start chrome "http://localhost"

echo Operazione completata. La finestra si chiudera a breve.
timeout /t 3 >nul
exit