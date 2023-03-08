<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trader>
 */
class TraderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'purchased' => fake()->numberBetween(20, 20000),
            'sold' => fake()->numberBetween(20, 20000),
            'earned' => fake()->numberBetween(20, 20000),
            'to_pay' => fake()->numberBetween(20, 20000),
            'to_collect' => fake()->numberBetween(20, 20000),
            'current_account' => fake()->numberBetween(20, 20000),
        ];
    }
}
