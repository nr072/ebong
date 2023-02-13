<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'en' => $this->faker->name(),
            'bn' => $this->faker->name(),
        ];
    }
}
