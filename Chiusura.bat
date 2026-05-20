@echo off
title Arresto Ambiente Docker

echo Spegnimento dei container Docker (docker compose down)...
cd /d "%~dp0"
docker compose down

echo Chiusura di Docker Desktop in corso...
taskkill /f /im "Docker Desktop.exe" /t >nul 2>&1

echo.
echo Ambiente arrestato con successo. La finestra si chiudera a breve.
timeout /t 3 >nul
exit