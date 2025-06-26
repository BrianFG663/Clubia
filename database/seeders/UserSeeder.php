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
                'dni' => 12345678,
                'state_id' => 1,
                'fecha_nacimiento' => '1997-01-01',
                'direccion' => 'Jujuy 420',
                'ciudad' => 'Gualeguaychú',
                'telefono' => '1234567890',
                'email' => 'fausto@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'nombre' => 'Brian',
                'apellido' => 'Gonzalez',
                'dni' => 12345679,
                'state_id' => 1,
                'fecha_nacimiento' => '1997-01-01',
                'direccion' => 'Del valle 663',
                'ciudad' => 'Gualeguaychú',
                'telefono' => '1234567891',
                'email' => 'brian@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'nombre' => 'Luz',
                'apellido' => 'Mercado',
                'dni' => 12345680,
                'state_id' => 1,
                'fecha_nacimiento' => '1997-01-01',
                'direccion' => 'Estrada 1245',
                'ciudad' => 'Gualeguaychú',
                'telefono' => '1234567892',
                'email' => 'luz@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];


        foreach ($users as $data) {
            $user = User::create($data);
            $user->assignRole($superAdminRole);
        }

        User::factory(5)->create();

    }
}
