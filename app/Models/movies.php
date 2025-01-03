<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movies extends Model
{
    use HasFactory;
    protected $table = 'movies';
    protected $fillable = [
        'movies_name',
        'movies_premiere_date'
    ];
}
