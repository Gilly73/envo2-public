<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalAccessTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('personal_access_tokens')->insert([
            'id'    => 1,
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name'       => 'TestToken',
            'token'      => '1f41784f979bfc0de49851f5e06c274da7f2bfbc15bab8d0e461f15712580b43',
            'abilities'  => '["*"]',
            'last_used_at' => NULL,
            'expires_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
