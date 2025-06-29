<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'birth_date' => $this->faker->date(),
            'avatar_url' => $this->faker->imageUrl(),
            'role' => 'USER',
            'is_active' => true,
        ];
    }
}
