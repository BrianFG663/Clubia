<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State; 

class StateSeeder extends Seeder
{

    public function run(): void
    {
        State::firstOrCreate(['nombre' => 'Activo']);
        State::firstOrCreate(['nombre' => 'Inactivo']);
    }

}
