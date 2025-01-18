<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinemas extends Model
{
    use HasFactory;
    protected $table = 'cinemas';
    protected $fillable = [
        'cinema_name',
        'address_1',
        'address_2',
        'zip',
        'city',
        'state',
        'country',
        'company_id',
        'image',
        'Difference_Address'
    ];
}
