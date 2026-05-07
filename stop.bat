@echo off
REM ============================================================
REM  Al-Ghani ERP - Stop All Services
REM ============================================================

title Al-Ghani ERP - Stopping...

echo.
echo Stopping Al-Ghani ERP services...
echo.

echo [1/3] Stopping Apache...
taskkill /F /IM httpd.exe >nul 2>&1

echo [2/3] Stopping Vite (Node)...
taskkill /F /FI "WINDOWTITLE eq Al-Ghani Frontend (Vite)*" >nul 2>&1

echo [3/3] MariaDB left running (used by other apps).
echo       To stop it manually: taskkill /F /IM mysqld.exe
echo.
echo All services stopped.
timeout /t 3 >nul
