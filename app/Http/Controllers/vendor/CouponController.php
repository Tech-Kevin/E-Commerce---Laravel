<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(15);

        return view('vendor.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('vendor.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        Coupon::create($validated);

        return redirect()->route('vendor.coupons.index')->with('success', 'Coupon created successfully');
    }

    public function edit($couponId)
    {
        $coupon = Coupon::findOrFail($couponId);

        return view('vendor.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $couponId)
    {
        $coupon = Coupon::findOrFail($couponId);

        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon->update($validated);

        return redirect()->route('vendor.coupons.index')->with('success', 'Coupon updated successfully');
    }

    public function destroy($couponId)
    {
        $coupon = Coupon::findOrFail($couponId);
        $coupon->delete();

        return redirect()->route('vendor.coupons.index')->with('success', 'Coupon deleted successfully');
    }
}
