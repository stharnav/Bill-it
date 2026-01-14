<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'company_name',
        'company_email',
        'company_address',
        'company_motto',
        'company_phone_no',
        'company_pan',
        'company_registration_no',
        'company_website',
    ];
}
