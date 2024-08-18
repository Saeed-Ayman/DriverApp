<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Image;
use App\Models\Review;
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
        $this->callOnce(CountrySeeder::class);
        $this->callOnce(CitySeeder::class);

//        User::factory(100)->create();

        Driver::factory(30)->create()->each(function (Driver $driver) {
            $driver->reviews()->saveMany(
                Review::factory()->count(rand(0, 20))->create([
                    'reviewable_type' => Driver::class,
                    'reviewable_id' => $driver->id,
                ])
            );

            $driver->Images()->saveMany(
                Image::factory()->count(rand(2, 6))->create([
                    'imageable_type' => Driver::class,
                    'imageable_id' => $driver->id,
                ])
            );
        });
    }
}
