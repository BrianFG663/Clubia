<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\SubActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSubActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $partners = Partner::all();
        $subActivities = SubActivity::all();

        foreach ($partners as $partner) {

            $randomSubActivities = $subActivities->random(rand(1, 3))->pluck('id')->toArray();
            $partner->subActivities()->attach($randomSubActivities);
        }
    }
}
