<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o conseguir el rol admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        // Obtener todos los permisos existentes (generados o definidos por Shield)
        $allPermissions = Permission::all();
        // Sincronizar (asignar) todos los permisos al rol admin
        $admin->syncPermissions($allPermissions);
    }

}

    

