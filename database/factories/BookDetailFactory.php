<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookDetail>
 */
class BookDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomElement([50000, 70000, 90000]);
        $promo = $price - fake()->randomElement([5000, 10000, 15000]);

        return [
            'book_id' => null, // sẽ gán khi gọi bằng quan hệ hoặc state()
            'language' => fake()->randomElement(['Vietnamese', 'English', 'Japanese']),
            'quantity' => fake()->numberBetween(10, 100),
            'price' => $price,
            'promotion_price' => $promo,
            'is_active' => fake()->boolean(90),
        ];
    }
}
