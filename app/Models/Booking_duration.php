<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_duration extends Model
{
    use HasFactory;
    protected $fillable = [
    'booking_id', 
    'duration_id'
];
public function booking() {
    return $this->belongsTo(Bookings::class);
}
}
