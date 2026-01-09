@echo off
chcp 65001 >nul
title World Building - Dev Mode

echo ========================================
echo    WORLD BUILDING - DEV MODE
echo ========================================
echo.
echo Mở 2 terminal:
echo   Terminal 1: php artisan serve
echo   Terminal 2: npm run dev
echo.

cd src
start cmd /k "php artisan serve"
timeout /t 2 >nul
start cmd /k "npm run dev"

echo Đã mở 2 terminal cho dev mode!
echo.
echo Server: http://127.0.0.1:8000
echo.
