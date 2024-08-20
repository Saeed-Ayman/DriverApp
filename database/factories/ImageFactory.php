<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'image_id' => "testing/img{$this->faker->numberBetween(1, 4)}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
