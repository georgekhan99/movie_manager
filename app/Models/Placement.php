<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;
    protected $table = 'cinema_placements';
    protected $fillable = [
        'cinema_id',
        'placement_name',
        'placement_description',
        'placement_width',
        'placement_height',
        'placement_colors',
        'placement_material',
        'placement_price',
    ];
}
