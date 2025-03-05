<?php

namespace Database\Factories;

use App\Models\Edition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'edition_id' => Edition::factory(),
            'reserved_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays($this->faker->numberBetween(7, 21)),
            'status' => $this->faker->randomElement(['approved', 'returned', 'cancelled']),
        ];
    }
}
