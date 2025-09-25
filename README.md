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
php artisan shield:generate --resource=SupplierResource


Ejecutar seeder: php artisan db:seed    























Tarea para los jobs de la facturacion mensual:

1. Abrí el Programador de tareas
Presioná Win + R, escribí taskschd.msc y presioná Enter

2. Crear nueva tarea
Clic en Crear tarea... (no “Crear tarea básica”)

3. Pestaña General
Nombre: Laravel Queue Worker
Descripción: Ejecuta el worker de Laravel cada 5 minutos
Marcar:
✅ “Ejecutar tanto si el usuario inició sesión como si no”
✅ “Ejecutar con los privilegios más altos”
Desmarcar:
❌ “No almacenar contraseña” (te pedirá la contraseña del usuario)

4. Pestaña Desencadenadores
Clic en Nuevo...
“Iniciar la tarea”: Al iniciar el sistema
Marcar:
✅ “Repetir cada”: 5 minutos
✅ “Durante”: Indefinidamente
❌ “Retrasar durante”: desactivado o en 0 minutos
✅ “Habilitado`
Clic en Aceptar

5. Pestaña Acciones
Clic en Nueva...
Acción: Iniciar un programa
Programa/script: C:\laragon\www\CCE\cron.bat
“Iniciar en (opcional)”: C:\laragon\www\CCE
Clic en Aceptar

6. Pestaña Condiciones
Desmarcar todo lo siguiente:
❌ “Iniciar solo si el equipo está inactivo”
❌ “Iniciar solo si está conectado a corriente alterna”
❌ “Detener si empieza a usar batería”
Opcional: marcar ✅ “Activar el equipo para ejecutar esta tarea”

7. Pestaña Configuración
Marcar:
✅ “Permitir que la tarea se ejecute a petición”
✅ “Ejecutar lo antes posible si no hubo inicio programado”
✅ “Si la tarea no se ejecuta, reiniciarla cada 1 minuto (máx. 10 veces)”
✅ “Detener si se ejecuta más de 1 hora”
✅ “Detener si no finaliza cuando se solicita”
“Si la tarea ya está en ejecución”: No iniciar una instancia nueva (o “Iniciar otra instancia” si querés que se repita sin esperar)

8. Guardar y probar
Clic en Aceptar
Ingresá tu contraseña si te la pide
Reiniciá la PC para verificar que se ejecuta automáticamente