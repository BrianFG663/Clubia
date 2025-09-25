#!/bin/bash
cd /var/www/CCE
php artisan queue:work --stop-when-empty
