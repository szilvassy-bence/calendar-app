<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingGroup;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Booking::factory()->create([
            'start_date' => Carbon::create(2024,9,8),
            'start_time' => Carbon::create(2024,9,8,8,0,0),
            'end_time' => Carbon::create(2024,9,8,10,0,0),
            'day' => 'sunday'
        ]);

        $this->generateBookings(
            start_date: carbon::create(2024,1,1),
            repetition: 'even_weeks',
            startHour: 10,
            endHour: 12,
            day: 'monday'
        );

        $this->generateBookings(
            start_date: carbon::create(2024,1,1),
            repetition: 'odd_weeks',
            startHour: 12,
            endHour: 16,
            day: 'wednesday'
        );

        $this->generateBookings(
            start_date: carbon::create(2024,1,1),
            repetition: 'every_weeks',
            startHour: 10,
            endHour: 16,
            day: 'friday'
        );

        $this->generateBookings(
            start_date: carbon::create(2024,6,1),
            repetition: 'every_weeks',
            startHour: 16,
            endHour: 20,
            day: 'thursday',
            end_date: carbon::create(2024,11,30)
        );
    }

    private function nextDateTime(DateTime $start_date, string $repetition, string $day): DateTime
    {
        $carbonStartDate = Carbon::instance($start_date);
        $next_day_date = $carbonStartDate->isDayOfWeek($day) ? clone $carbonStartDate : $carbonStartDate->next($day);
        if (($repetition === 'even_weeks' && $next_day_date->weekOfYear() % 2 === 0) ||
            ($repetition === 'odd_weeks'  && $next_day_date->weekOfYear() % 2 === 1) ||
            ($repetition === 'every_weeks'))
        {
            return $next_day_date;
        }
        else
        {
            return $next_day_date->addWeeks();
        }
    }

    private function generateBookings(
        DateTime $start_date,
        string $repetition,
        int $startHour,
        int $endHour,
        string $day,
        DateTime $end_date = null,
        int $maxBookings = 10)
    {
        $start_time = $this->nextDateTime($start_date, $repetition, $day);
        $end_time = clone $start_time;
        $start_time->setTime( $startHour,0,0);
        $end_time->setTime( $endHour,0,0);

        $bookingGroup = BookingGroup::create();

        if ($end_date) {
            while ($start_time <= $end_date) {
                Booking::factory()->create([
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'day' => $day,
                    'repetition' => $repetition,
                    'booking_group_id' => $bookingGroup->id
                ]);

                $this->incrementWeeks($start_time, $end_time, $repetition);
            }
        } else {
            for ($i = 0; $i < $maxBookings; $i++) {
                Booking::factory()->create([
                    'start_date' => $start_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'day' => $day,
                    'repetition' => $repetition,
                    'booking_group_id' => $bookingGroup->id
                ]);

                $this->incrementWeeks($start_time, $end_time, $repetition);
            }
        }
    }

    private function incrementWeeks(DateTime $start_time, DateTime $end_time, string $repetition)
    {
        if ($repetition === 'odd_weeks'||$repetition === 'even_weeks') {
            $start_time->addWeeks(2);
            $end_time->addWeeks(2);
        } else{
            $start_time->addWeeks();
            $end_time->addWeeks();
        }
    }
}
