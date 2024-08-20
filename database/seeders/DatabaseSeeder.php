<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->callOnce(CountrySeeder::class);
        $this->callOnce(CitySeeder::class);
        $this->call(DriverSeeder::class);
    }
}
