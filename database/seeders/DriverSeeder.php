<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Image;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::factory(30)->create()->each(function (Driver $driver) {
            $driver->reviews()->saveMany(
                Review::factory()->count(rand(0, 20))->create([
                    'reviewable_type' => Driver::class,
                    'reviewable_id' => $driver->id,
                ])
            );

            $driver->images()->saveMany(
                Image::factory()->count(rand(2, 6))->create([
                    'imageable_type' => Driver::class,
                    'imageable_id' => $driver->id,
                ])
            );
        });
    }
}
