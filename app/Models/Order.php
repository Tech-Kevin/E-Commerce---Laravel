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
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
    ];

    public const CANCELLABLE_STATUSES = ['pending', 'processing'];

    public function canBeCancelled(): bool
    {
        return in_array($this->status, self::CANCELLABLE_STATUSES, true);
    }

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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function returnRequest()
    {
        return $this->hasOne(ProductReturn::class);
    }
}
