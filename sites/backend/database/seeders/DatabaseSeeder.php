<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Order matters if there are foreign key dependencies.
        $this->call([
            CouchTypesSeeder::class,
            LegsSeeder::class,
            FabricsSeeder::class,
            SeatersSeeder::class,
            StylesSeeder::class,
            UsersSeeder::class,
            PersonalAccessTokensSeeder::class,
        ]);
    }
}
