<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                  'name' => 'Cairo',
                  'country_id' => Country::factory(),
                  'postal_code' => $this->faker->numerify('#####'), // Default shorter format for postal codes
                  'status' => 1,
        ];
    }
}
