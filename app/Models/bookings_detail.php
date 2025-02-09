<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookings_detail extends Model
{
    use HasFactory;
    protected $table = 'bookings_detail';
    public $timestamps = false;
    protected $fillable = [
        'booking_id',
        'placement_id',
        'duration_id'
    ];
}
