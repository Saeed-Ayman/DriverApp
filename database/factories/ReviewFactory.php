<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $created_at = $this->faker->dateTimeBetween('-2 months');

        return [
            'created_at' => $created_at,
            'updated_at' => $this->faker->randomElement([$this->faker->dateTimeBetween($created_at), $created_at]),
            'stars' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->sentences(asText: true),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
