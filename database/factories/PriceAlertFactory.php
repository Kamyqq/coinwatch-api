<?php

namespace Database\Factories;

use App\Models\Cryptocurrency;
use App\Models\PriceAlert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceAlert>
 */
class PriceAlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cryptocurrency_id' => Cryptocurrency::factory(),
            'target_price' => $this->faker->randomFloat(2, 0.01, 50000),
            'direction' => $this->faker->randomElement(['above', 'below']),
            'is_triggered' => $this->faker->boolean(10),
        ];
    }
}
