<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $country = Country::with('cities')
            ->whereRaw('(select count(*) from `cities` where `countries`.`id` = `cities`.`country_id`) > 0')
            ->get()->random(1)->first();

        $city = $country->cities()->get()->random(1)->value('id');

        $services = [];

        for ($i = 0; $i < $this->faker->numberBetween(1, 10); $i++) {
            $services[] = [
                "service".($i + 1) => $this->faker->numberBetween(10, 100).'$'
            ];
        }

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'excerpt' => $this->faker->text(30),
            'whatsapp' => $this->faker->e164PhoneNumber(),
            'phone' => $this->faker->e164PhoneNumber(),
            'landline' => $this->faker->e164PhoneNumber(),
            'services' => $services,
            'location' => [
                'lat' => $this->faker->latitude(),
                'lng' => $this->faker->longitude(),
            ],
            'country_id' => $country->value('id'),
            'city_id' => $city,
        ];
    }
}
