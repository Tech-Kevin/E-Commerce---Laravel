<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'delivery_boy_id',
        'order_number',
        'full_name',
        'phone',
        'address',
        'city',
        'pincode',
        'subtotal',
        'shipping',
        'grand_total',
        'status',
        'payment_method',
        'payment_status',
        'razorpay_payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'delivery_boy_id');
    }

    public function deliveryOtps()
    {
        return $this->hasMany(DeliveryOtp::class);
    }
}
