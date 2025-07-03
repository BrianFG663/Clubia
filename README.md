Correr migraciones : php artisan migrate:fresh
Generar roles y permisos : php artisan shield:install
Ejecutar seeder: php artisan db:seed


permisos super-admin
php artisan shield:generate --resource=UserResource
php artisan shield:generate --resource=PartnerResource
php artisan shield:generate --resource=ActivityResource
php artisan shield:generate --resource=InstitutionResource
