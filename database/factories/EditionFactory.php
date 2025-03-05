<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Edition>
 */
class EditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'book_id' => Book::factory(),
            'condition' => $this->faker->randomElement(['new', 'used', 'damaged']),
            'repair_count' => $this->faker->numberBetween(0, 5),
            'available' => $this->faker->boolean(80),
        ];
    }
}
