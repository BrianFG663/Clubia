<?php

namespace Database\Seeders;

use App\Models\SubActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubActivityUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userIds = User::pluck('id')->toArray();
        $subActivityIds = SubActivity::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            DB::table('sub_activity_user')->insert([
                'user_id' => $userId,
                'sub_activity' => $subActivityIds[array_rand($subActivityIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}