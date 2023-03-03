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
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'he_sold_us' => fake()->numberBetween(20, 20000),
            'we_sold_him' => fake()->numberBetween(20, 20000),
            'we_earned_from_him' => fake()->numberBetween(20, 20000),
            'we_owe_him' => fake()->numberBetween(20, 20000),
            'he_owes_us' => fake()->numberBetween(20, 20000),
            'current_account' => fake()->numberBetween(20, 20000),
        ];
    }
}
