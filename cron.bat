@echo off
cd /d %~dp0
php artisan queue:work --stop-when-empty
