<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['pizza_id', 'custom_toppings', 'total_price', 'payment_method'];

    protected $casts = [
        'custom_toppings' => 'array',
    ];
}
