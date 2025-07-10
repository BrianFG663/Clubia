<?php

namespace Database\Seeders;

use App\Models\MemberType;
use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberTypePartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = Partner::all();
        $memberTypes = MemberType::all();

    foreach ($partners as $partner) {
        
        $randomTypes = $memberTypes->random(rand(1, 2))->pluck('id')->toArray();
        $partner->memberTypes()->attach($randomTypes);
    }
    }
}
