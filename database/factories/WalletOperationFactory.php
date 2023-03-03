<?php

namespace Database\Factories;

use App\Helper\walletOperationType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Enum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletOperation>
 */
class WalletOperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "amount" => $this->faker->randomFloat(2, 10, 100),
            "rate" => $this->faker->numberBetween(1, 70000),
            "currency_was" => $this->faker->numberBetween(1, 70000),
            "currency_became" => $this->faker->numberBetween(1, 70000),
            "type" => $this->faker->randomElement([
                "payment_in",
                "payment_out",
                "invoice_in",
                "invoice_out",
                "expense",
            ]) ,
            "model_id" => 1,
            "description" => $this->faker->word(),
            'branch_id' => $this->faker->numberBetween(1, 5),
            'currency_id' => $this->faker->numberBetween(1, 5),
            'user_id' => 1,
            'created_at' => $this->faker->dateTimeBetween("now", "1 year"),
        ];
    }
}
