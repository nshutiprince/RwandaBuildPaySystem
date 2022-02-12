<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => Str::random(5) . " Product",
            "price" => $this->faker->randomFloat(2, 100, 1000),
            "quantity" => 9,
        ];
    }
}
