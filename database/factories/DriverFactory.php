<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        $created_at = $this->faker->dateTimeBetween('-1 years');

        return [
            'created_at' => $created_at,
            'updated_at' => $this->faker->randomElement([$this->faker->dateTimeBetween('-1 years'), $created_at]),
            'name' => $this->faker->name(),
            'phone' => $this->faker->e164PhoneNumber(),
            'description' => $this->faker->sentences(asText: true),
            'whatsapp' => $this->faker->e164PhoneNumber(),
            'country' => $this->faker->country(),
            'government' => $this->faker->streetAddress(),
        ];
    }
}
