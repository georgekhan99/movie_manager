<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'label_id',
        'company_legalname',
        'company_brand_name',
        'company_organization',
        'company_parent_company',
        'company_CVR',
        'company_address_1',
        'company_address_2',
        'company_zip_code',
        'company_city',
        'company_state',
        'company_Country'

    ];
}
