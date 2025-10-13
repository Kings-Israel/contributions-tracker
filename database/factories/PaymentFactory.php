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
            'reference_id' => \Illuminate\Support\Str::upper((\Illuminate\Support\Str::random(6))),
            'payment_type' => $this->faker->randomElement(['project contribution', 'offertory']),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'created_at' => $this->faker->randomElement([
                now()->subDays(6),
                now()->subDays(15),
                now()->subDays(20),
                now()->subDays(15),
                now()->subDays(40),
                now()->subDays(23),
                now()->subDays(90),
                now()->subDays(60),
                now()->subDays(30),
                now()->subDays(45),
                now()->subDays(10),
                now()->subDays(70),
            ])
        ];
    }
}
