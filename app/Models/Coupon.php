<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'description', 'discount_type', 'discount_value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'used_count', 'valid_from', 'valid_until', 'is_active'];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        return $this->is_active 
            && $this->used_count < $this->usage_limit
            && now()->between($this->valid_from, $this->valid_until);
    }

    public function calculateDiscount($amount)
    {
        if ($amount < $this->min_order_amount) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? ($amount * $this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount_amount !== null) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }
}
