<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expense_type' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'payment_reference_id' => \Illuminate\Support\Str::uuid(),
            'month' => $this->faker->date(),
            'status' => 'pending',
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'approved_by' => null,
        ];
    }
}
