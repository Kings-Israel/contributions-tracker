<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(5, 75),
            'gender' => $this->faker->randomElement(['male', 'female']),
        ];
    }
}
