<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouchTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('couch_types')->insert([
            [
                'id'         => 1,
                'name'       => 'indoor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'outdoor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
