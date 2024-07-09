<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'day',
        'repetition',
        'user',
        'booking_group_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function bookingGroup(): BelongsTo
    {
        return $this-> belongsTo(BookingGroup::class);
    }


}
