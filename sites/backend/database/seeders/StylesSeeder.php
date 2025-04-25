<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StylesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('styles')->insert([
            ['id' => 1, 'couch_type_id' => 1, 'name' => 'Modern Indoor Comfort','base_price' => 100.00,'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'couch_type_id' => 1, 'name' => 'Classic Indoor Comfort','base_price' => 200.00,'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'couch_type_id' => 2, 'name' => 'Morden Outdoor Comfort','base_price' => 80.00,'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'couch_type_id' => 2, 'name' => 'Traditional Outdoor Comfort','base_price' => 160.00,'created_at' => now(), 'updated_at' => now()],
        ]);

        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
