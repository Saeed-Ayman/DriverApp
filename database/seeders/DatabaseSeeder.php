<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();

        $this->callOnce(CountrySeeder::class);
        $this->callOnce(CitySeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(DriverSeeder::class);
    }
}
