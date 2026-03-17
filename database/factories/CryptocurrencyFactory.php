<?php

namespace Database\Factories;

use App\Models\Cryptocurrency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cryptocurrency>
 */
class CryptocurrencyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->word()),
            'symbol' => strtoupper($this->faker->lexify('???')),
            'price' => $this->faker->randomFloat(2, 0.01, 100000),
        ];
    }
}
