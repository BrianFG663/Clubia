<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $condiciones = [
            'Responsable Inscripto',
            'Monotributo',
            'Exento',
            'Consumidor Final',
            'No Responsable',
        ];

        foreach ($condiciones as $condicion) {
            Condition::create(['nombre' => $condicion]);
        }
    }
}
