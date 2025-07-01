<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'author_id' => null, // Gán sau bằng seeder hoặc state()
            'publisher_id' => null,
            'category_id' => null,
            'description' => fake()->paragraph(4),
        ];
    }
}
