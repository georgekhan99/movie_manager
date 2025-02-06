<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_placement extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id', 
        'placement_id'
    ];

    public function booking() {
        return $this->belongsTo(Bookings::class);
    }

}
