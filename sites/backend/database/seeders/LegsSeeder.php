<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('legs')->insert([
            ['id' => 1, 'couch_type_id' => 1,'price' => 10.00,'description' => 'black wooden leg','created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'couch_type_id' => 1, 'price' => 30.00,'description' => 'gold metal leg','created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'couch_type_id' => 1, 'price' => 20.00,'description' => 'brown wooden leg','created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'couch_type_id' => 2, 'price' => 30.00,'description' => 'brown protected wooden leg','created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'couch_type_id' => 2, 'price' => 20.00,'description' => 'white metal leg','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
