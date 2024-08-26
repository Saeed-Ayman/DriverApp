<?php

namespace Database\Seeders;

use App\Http\Controllers\LocationController;
use App\Models\Driver;
use App\Models\Image;
use App\Models\Location;
use App\Models\Review;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::factory(30)->create()->each(function (Location $location) {
            $location->reviews()->saveMany(
                Review::factory()->count(rand(0, 20))->create([
                    'reviewable_type' => Driver::class,
                    'reviewable_id' => $location->id,
                ])
            );

            $location->images()->saveMany(
                Image::factory()->count(rand(2, 6))->create([
                    'imageable_type' => Driver::class,
                    'imageable_id' => $location->id,
                ])
            );
        });
    }
}
