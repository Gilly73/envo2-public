<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FabricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fabrics')->insert([
            ['id' => 1, 'couch_type_id' => 1, 'price' => 300.00, 'material' => 'velvet', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'couch_type_id' => 1, 'price' => 100.00, 'material' => 'cotton', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'couch_type_id' => 1, 'price' => 400.00, 'material' => 'leather', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'couch_type_id' => 2, 'price' => 100.00, 'material' => 'wood', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'couch_type_id' => 2, 'price' => 200.00, 'material' => 'metal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
