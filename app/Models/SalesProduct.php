<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sales;

class SalesProduct extends Model
{
    protected $table = 'sales_product';

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }
}
