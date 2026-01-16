<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'invoice_no',
        'description',
        'mode_of_payment',
        'payment_details',
        'discount',
    ];
}
