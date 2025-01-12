<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    use HasFactory;
    protected $table = 'durations';
    protected $fillable = ['start_date', 'delivery_date', 'production_deadline'];
    protected $dates = ['start_date', 'delivery_date', 'production_deadline']; // Ensure these are treated as date fields
}
