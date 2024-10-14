<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Image;
use App\Models\Location;
use App\Models\LocationCategory;
use App\Models\Review;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        collect(['bazaar', 'restraint', 'caffe', 'museum', 'bark', 'zoo'])->each(
            fn($category_location) => LocationCategory::create(['name' => $category_location])
        );

        Location::factory(30)->create()->each(function (Location $location) {
            $location->reviews()->saveMany(Review::factory(rand(0, 20))->make());
            $location->images()->saveMany(Image::factory(rand(1, 5))->make());

            Image::create([
                'imageable_type' => Location::class . '\\logo',
                'imageable_id' => $location->id,
                'image_id' => Location::DEFAULT_LOGO,
                'image_url' => Image::getUrl(Location::DEFAULT_LOGO),
            ]);
        });
    }
}
