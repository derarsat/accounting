<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
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
            'symbol' => fake()->currencyCode(),
            'rate' => fake()->randomFloat(2, 0, 100),
            'amount' => fake()->randomFloat(2, 0, 500000),
        ];
    }
}
