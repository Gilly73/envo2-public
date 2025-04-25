<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id'    => 1,
            'name'       => 'Seeta Gill Beaerer Token',
            'email'      => 'seeta@token.com',
            'email_verified_at'  => NULL,
            'password' => '$2y$10$3/vW13NvDc7b.sMi9Db2euD9q6/3E6qCL0kn8LZ.x8VTeolAHu2ti',
            'remember_token' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
