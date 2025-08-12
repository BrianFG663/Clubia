Correr migraciones : php artisan migrate:fresh
Generar roles y permisos : php artisan shield:install

permisos super-admin
php artisan shield:generate --resource=UserResource
php artisan shield:generate --resource=PartnerResource
php artisan shield:generate --resource=ActivityResource
php artisan shield:generate --resource=InstitutionResource
php artisan shield:generate --resource=SubActivityResource
php artisan shield:generate --resource=MemberTypeResource
php artisan shield:generate --resource=ProductResource
php artisan shield:generate --resource=CategoryResource
php artisan shield:generate --resource=SaleResource



Ejecutar seeder: php artisan db:seed