<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'bill_no',
        'description',
        'mode_of_payment',
        'payment_details',
        'discount',
        'tax',
        'customer_name',
        'is_refund',
    ];

    public function items()
    {
        return $this->hasMany(SalesProduct::class, 'sale_id');
    }
}
