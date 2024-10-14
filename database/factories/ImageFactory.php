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
        $img_id = "testing/img".fake()->numberBetween(1, 4);
        return [
            'image_id' => $img_id,
            'image_url' => Image::getUrl($img_id),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
