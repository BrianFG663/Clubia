<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::firstOrCreate(['name' => 'Activo']);
        State::firstOrCreate(['name' => 'Inactivo']);

    }
}
