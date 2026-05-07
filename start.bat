@echo off
REM ============================================================
REM  Al-Ghani ERP - One-Click Start
REM  Starts MariaDB, Apache (backend on :8000), Vite (frontend on :5173),
REM  then opens the app in your browser.
REM ============================================================

setlocal
title Al-Ghani ERP Launcher

REM ---- Project paths (do not edit unless you move the project) ----
set "PROJECT_ROOT=%~dp0"
set "FRONTEND_DIR=%PROJECT_ROOT%frontend"
set "XAMPP_DIR=C:\xampp"
set "APACHE_EXE=%XAMPP_DIR%\apache\bin\httpd.exe"
set "MYSQL_EXE=%XAMPP_DIR%\mysql\bin\mysqld.exe"
set "MYSQL_PING=%XAMPP_DIR%\mysql\bin\mysqladmin.exe"
set "FRONTEND_URL=http://localhost:5173"

echo.
echo ===============================================
echo   Al-Ghani ERP - Starting all services...
echo ===============================================
echo.

REM ---- 1. Start MariaDB if not running ----
echo [1/3] Checking MariaDB...
"%MYSQL_PING%" -u root ping >nul 2>&1
if errorlevel 1 (
    echo       Starting MariaDB...
    start "Al-Ghani MariaDB" /MIN "%MYSQL_EXE%" --defaults-file="%XAMPP_DIR%\mysql\bin\my.ini" --standalone
    timeout /t 4 /nobreak >nul
) else (
    echo       MariaDB is already running.
)

REM ---- 2. Start Apache (backend) if not running ----
echo [2/3] Checking Apache backend...
tasklist /FI "IMAGENAME eq httpd.exe" 2>nul | find /I "httpd.exe" >nul
if errorlevel 1 (
    echo       Starting Apache on port 8000...
    start "Al-Ghani Backend (Apache)" /MIN "%APACHE_EXE%"
    timeout /t 3 /nobreak >nul
) else (
    echo       Apache is already running.
)

REM ---- 3. Start Vite (frontend) ----
echo [3/3] Starting Vite frontend on port 5173...
start "Al-Ghani Frontend (Vite)" cmd /k "cd /d "%FRONTEND_DIR%" && npm run dev"

REM ---- 4. Wait, then open browser ----
echo.
echo Waiting for frontend to be ready...
timeout /t 6 /nobreak >nul

echo Opening browser...
start "" "%FRONTEND_URL%"

echo.
echo ===============================================
echo   All services started!
echo   Backend:  http://127.0.0.1:8000
echo   Frontend: %FRONTEND_URL%
echo   Login:    admin@alghani.com  /  Admin@123
echo ===============================================
echo.
echo This window can be closed.
timeout /t 5 >nul
endlocal
