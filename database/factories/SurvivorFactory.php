<?php

namespace Database\Factories;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurvivorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = Gender::inRandomOrder()->first();

        return [
            'name' => $this->faker->name($gender->name),
            'age' => rand(18, 48),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'gender_id' => $gender->id
        ];
    }
}
