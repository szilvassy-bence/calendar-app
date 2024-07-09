<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::create(2024,9,8,8,0,0);
        $day = $date->day;

        return [
            'start_date' => $date->toDate(),
            'start_time' => $date,
            'end_time' => $date->addHours(2),
            'repetition' => 'no',
            'day' => $day,
            'user' => fake()->name,
        ];
    }
}
