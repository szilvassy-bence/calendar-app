<?php

namespace App\Services;

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingService
{
    // if they have collision returns true
    // if they do not have collision returns false
    public function isOccupied(Booking $dbBooking, Booking $request): bool {
        $dbBookingEndDate = $dbBooking->end_date ?? Carbon::create(9000);
        $requestEndDate = $request->end_date ?? Carbon::create(9000);

        if ($dbBooking->repetition === BookingRepetition::NO && $request->repetition === BookingRepetition::NO)
        {
            return $dbBooking->start_date->isSameDay($request->start_date);
        }
        else if ($dbBooking->repetition === BookingRepetition::NO && $request->repetition !== BookingRepetition::NO)
        {
            return $dbBooking->start_date->greaterThanOrEqualTo($request->start_date)
                && $dbBooking->start_date->lessThanOrEqualTo($requestEndDate);
        }
        else if ($dbBooking->repetition !== BookingRepetition::NO && $request->repetition === BookingRepetition::NO)
        {
            return $dbBooking->start_date->lessThanOrEqualTo($request->start_date)
                && $dbBookingEndDate->greaterThanOrEqualTo($request->start_date);
        }
        else if ($dbBooking->repetition === $request->repetition
                || $dbBooking->repetition === BookingRepetition::WEEKS
                || $request->repetition === BookingRepetition::WEEKS
        )
        {
            return $dbBooking->start_date->lessThanOrEqualTo($requestEndDate)
                    && $dbBookingEndDate->greaterThanOrEqualTo($request->start_date);
        }
        return false;
    }

    public function isInOpeningHours(Booking $booking) {
        $openingHour = Config::get('office.opening_hour');
        $closingHour = Config::get('office.closing_hour');
        var_dump($openingHour);

        if ($booking->day === Day::SATURDAY || $booking->day === Day::SUNDAY) {
            return false;
        }
        if ($booking->start_time < Carbon::createFromTime(8)) {
            return false;
        }
        if ($booking->end_time > Carbon::createFromTime(20)) {
            return false;
        }
        return true;
    }

}
