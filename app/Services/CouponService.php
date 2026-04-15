<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    /**
     * Validate coupon code
     */
    public function validate($couponCode, $orderAmount)
    {
        $coupon = Coupon::where('code', strtoupper($couponCode))->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Coupon code not found'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Coupon is expired or inactive'];
        }

        if ($orderAmount < $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount of ₹{$coupon->min_order_amount} required",
            ];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($couponCode, $orderAmount)
    {
        $validation = $this->validate($couponCode, $orderAmount);

        if (!$validation['valid']) {
            return 0;
        }

        return $validation['coupon']->calculateDiscount($orderAmount);
    }

    /**
     * Apply coupon (increment usage count)
     */
    public function apply($couponCode)
    {
        $coupon = Coupon::where('code', strtoupper($couponCode))->first();

        if ($coupon) {
            $coupon->increment('used_count');
        }

        return $coupon;
    }

    /**
     * Revert coupon usage (decrement usage count)
     */
    public function revert($couponCode)
    {
        $coupon = Coupon::where('code', strtoupper($couponCode))->first();

        if ($coupon) {
            $coupon->decrement('used_count');
        }

        return $coupon;
    }
}
