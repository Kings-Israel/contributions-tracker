<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference_id' => \Illuminate\Support\Str::uuid(),
            'payment_type' => $this->faker->randomElement(['contribution', 'offertory']),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'month' => $this->faker->date(),
        ];
    }
}
