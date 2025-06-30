<?php

namespace Database\Seeders;

use App\Models\MemberType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberTypeUserSeeder extends Seeder
{
     public function run()
    {
        $memberTypeIds = MemberType::pluck('id')->toArray();
        $users = User::all();

        foreach ($users as $user) {
            DB::table('member_type_user')->insert([
                'user_id' => $user->id,
                'member_type_id' => $memberTypeIds[array_rand($memberTypeIds)],
            
            ]);
        }
    }
}
