<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'amount', 'currency', 'payment_method', 'transaction_id', 'status', 'paid_at', 'gateway_response'];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_response' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
