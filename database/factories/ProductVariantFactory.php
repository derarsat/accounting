<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "product_id" => $this->faker->numberBetween(1, 10),
            "quantity_id" => $this->faker->numberBetween(1, 10),
            "trader_id" => $this->faker->numberBetween(1, 40),
            "quantity_value" => $this->faker->numberBetween(1, 600),
            "purchased" => $this->faker->randomFloat(2, 4, 90),
            "min_price" => $this->faker->randomFloat(2, 4, 90),
            "price" => $this->faker->randomFloat(2, 4, 90),
            "image" => $this->faker->imageUrl(),
            "barcode" => $this->faker->word(),
            "identifier" => $this->faker->word(),
            "weight_value" => $this->faker->numberBetween(1, 500),
            "extra_quantity" => $this->faker->numberBetween(1, 500),
            "expire" => $this->faker->date(),

        ];
    }
}
