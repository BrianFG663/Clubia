<?php

namespace Database\Seeders;

use App\Models\MemberType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MemberType::factory(5)->create();
    }
}
