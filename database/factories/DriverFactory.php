<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        $created_at = $this->faker->dateTimeBetween('-1 years');

        $country = Country::has('locations')->get()->random();
        $city = $country->cities()->has('locations')->get()->random();

        return [
            'created_at' => $created_at,
            'updated_at' => $this->faker->randomElement([$this->faker->dateTimeBetween('-1 years'), $created_at]),
            'name' => $this->faker->name(),
            'phone' => $this->faker->e164PhoneNumber(),
            'description' => $this->faker->sentences(asText: true),
            'whatsapp' => $this->faker->e164PhoneNumber(),
            'country' => $this->faker->country(),
            'government' => $this->faker->streetAddress(),
            'country_id' => $country->id,
            'city_id' => $city->id,
        ];
    }
}
