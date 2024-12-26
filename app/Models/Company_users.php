<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_users extends Model
{
    use HasFactory;
    protected $table = 'company_users';
    protected $fillable = [
        'users_id',
        'company_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
