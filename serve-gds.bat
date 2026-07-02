@echo off
cd /d "%~dp0"
echo.
echo  GDS Groupe DLIMI Services
echo  Lien : http://127.0.0.1:8010/login
echo.
php artisan serve --host=127.0.0.1 --port=8010
