<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Image;
use App\Models\Location;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::factory(30)->create()->each(function (Driver $driver) {
            $driver->reviews()->saveMany(Review::factory(rand(0, 20))->make());
            $driver->images()->saveMany(Image::factory(rand(1, 4))->make());

            Image::create([
                'imageable_type' => Driver::class . '\\avatar',
                'imageable_id' => $driver->id,
                'image_id' => User::DEFAULT_AVATAR,
                'image_url' => Image::getUrl(User::DEFAULT_AVATAR),
            ]);
        });
    }
}
