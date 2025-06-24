<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        
        User::create([
            'first_name' => 'Fausto',
            'last_name' => 'Parada',
            'dni' => 12345678,
            'state_id' => 1, 
            'birth_date' => '1997-01-01',
            'address' => 'Jujuy 420',
            'city' => 'Gualeguaychú',
            'phone' => '1234567890',
            'email' => 'fausto@gmail.com',
            'password' => bcrypt('123456'),
        ]);


        User::create([
            'first_name' => 'Brian',
            'last_name' => 'Gonzalez',
            'dni' => 12345678,
            'state_id' => 1, 
            'birth_date' => '1997-01-01',
            'address' => 'Del valle 663',
            'city' => 'Gualeguaychú',
            'phone' => '1234567890',
            'email' => 'brian@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'first_name' => 'Luz',
            'last_name' => 'Mercado',
            'dni' => 12345678,
            'state_id' => 1, 
            'birth_date' => '1997-01-01',
            'address' => 'Estrada 1245',
            'city' => 'Gualeguaychú',
            'phone' => '1234567890',
            'email' => 'luz@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    } 
  
}

