<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // Obtener todos los permisos existentes generados por Shield
        $allPermissions = Permission::all();

        // Asignar todos los permisos al rol admin 
        $admin->syncPermissions($allPermissions);
    }
}

    

