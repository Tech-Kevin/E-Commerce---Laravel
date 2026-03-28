<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone'     => 'required|string|min:10|max:15',
            'address'   => 'required|string|max:255',
            'city'      => 'required|string|max:100',
            'pincode'   => 'required|string|max:10',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        $shipping = 0;
        foreach ($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
            $shipping += $item['shipping_charge'] ?? 0;
        }
        $grandTotal = $subtotal + $shipping;

        $order = Order::create([
            'user_id'        => Auth::id(),
            'order_number'   => 'ORD' . strtoupper(uniqid()),
            'full_name'      => $request->full_name,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city'           => $request->city,
            'pincode'        => $request->pincode,
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'grand_total'    => $grandTotal,
            'payment_method' => $request->payment_method ?? 'cod',
        ]);

        foreach ($cart as $productId => $item) {
            $order->items()->create([
                'product_id'      => $productId,
                'product_name'    => $item['name'],
                'price'           => $item['sale_price'] ?? $item['price'],
                'quantity'        => $item['quantity'],
                'shipping_charge' => $item['shipping_charge'] ?? 0,
            ]);
        }

        session()->forget('cart');

        // Load relationships needed for invoice/email
        $order->load('items');

        // Send confirmation email with PDF invoice
        $userEmail = Auth::user()->email;
        try {
            Mail::to($userEmail)->send(new OrderConfirmationMail($order));
        } catch (\Throwable $e) {
            Log::error('Order email failed: ' . $e->getMessage(), ['order' => $order->order_number]);
        }

        // Send SMS notification
        try {
            $sms     = app(SmsService::class);
            $message = "Hi {$order->full_name}, your order #{$order->order_number} has been placed successfully! Total: Rs.{$order->grand_total}. Thank you for shopping with Ekka_Lv.";
            $sms->send($order->phone, $message);
        } catch (\Throwable $e) {
            Log::error('Order SMS failed: ' . $e->getMessage(), ['order' => $order->order_number]);
        }

        return redirect()->route('customer.orders')->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();
        return view('customer.orders', compact('orders'));
    }
}
