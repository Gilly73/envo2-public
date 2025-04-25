<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seaters')->insert([
            ['id' => 1, 'seat_count' => 2, 'couch_type_id' => 1, 'price' => 50.00,'description' => '2 seater - indoor','created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'seat_count' => 3, 'couch_type_id' => 1, 'price' => 100.00,'description' => '3 seater - indoor','created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'seat_count' => 4, 'couch_type_id' => 1, 'price' => 150.00,'description' => '4 seater - indoor','created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'seat_count' => 2, 'couch_type_id' => 2, 'price' => 20.00,'description' => '2 seater - outdoor','created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'seat_count' => 3, 'couch_type_id' => 2, 'price' => 30.00,'description' => '3 seater - outdoor','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
