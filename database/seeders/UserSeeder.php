<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();

        $users = [
            [
                'nombre' => 'Fausto',
                'apellido' => 'Parada',
                'email' => 'fausto@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'nombre' => 'Brian',
                'apellido' => 'Gonzalez',
                'email' => 'brian@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'nombre' => 'Luz',
                'apellido' => 'Mercado',
                'email' => 'luz@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];


        foreach ($users as $data) {
            $user = User::create($data);
            $user->assignRole($superAdminRole);
        }

    }
}
