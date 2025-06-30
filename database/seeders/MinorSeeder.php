<?php

namespace Database\Seeders;

use App\Models\Minor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Minor::factory(4)->create();
    }
}
