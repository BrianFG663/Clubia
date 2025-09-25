<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // <- corregido

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o conseguir el permiso access_admin_panel
        $accessAdmin = Permission::firstOrCreate([
            'name' => 'access_admin_panel',
            'guard_name' => 'web',
        ]);

        // Crear o conseguir el rol admin y asignarle el permiso
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $adminRole->givePermissionTo($accessAdmin);

        $users = [
            [
                'nombre' => 'Fausto',
                'apellido' => 'Parada',
                'email' => 'fausto@gmail.com',
                'password' => bcrypt('123456'),
                'institution_id' => 1,
            ],
            [
                'nombre' => 'Brian',
                'apellido' => 'Gonzalez',
                'email' => 'brian@gmail.com',
                'password' => bcrypt('123456'),
                'institution_id' => 1,
            ],
            [
                'nombre' => 'Luz',
                'apellido' => 'Mercado',
                'email' => 'luz@gmail.com',
                'password' => bcrypt('123456'),
                'institution_id' => 2,
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $user->assignRole($adminRole);
        }
    }
}
