<?php

namespace Database\Seeders;

use App\Models\SaleDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleDetail::factory(15)->create();
    }
}
