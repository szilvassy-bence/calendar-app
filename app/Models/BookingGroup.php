<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingGroup extends Model
{
    use HasFactory;

    protected $table = "booking_groups";

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
