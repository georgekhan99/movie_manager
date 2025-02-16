<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'user_id',
    ];

    public function placements() {
        return $this->hasMany(Booking_Placement::class);
    }

    public function durations() {
        return $this->hasMany(Booking_Duration::class);
    }

}
