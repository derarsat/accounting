<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "material_id" => $this->faker->numberBetween(1, 3),
            "location" => $this->faker->word(),
            "weight" => $this->faker->randomElement(['KG', "Liter"]),
            "total_earnings" => $this->faker->numberBetween(1, 4500),
            "total_pieces_sold" => $this->faker->numberBetween(1, 32000),
            "alert_when_remaining" => $this->faker->numberBetween(1, 12),
            "category_id" => $this->faker->numberBetween(1, 10),
            "branch_id" => $this->faker->numberBetween(1, 2),
        ];
    }
}
