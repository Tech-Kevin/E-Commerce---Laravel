<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderCancellationService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

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
            'payment_method' => 'required|in:cod,razorpay',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check stock availability before placing order
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['quantity']) {
                $name = $product ? $product->name : 'Unknown product';
                return redirect()->back()->with('error', "Insufficient stock for \"{$name}\". Available: " . ($product->stock ?? 0));
            }
        }

        $subtotal = 0;
        $shipping = 0;
        foreach ($cart as $item) {
            $price = $item['sale_price'] ?? $item['price'];
            $subtotal += $price * $item['quantity'];
            $shipping += $item['shipping_charge'] ?? 0;
        }
        $grandTotal = $subtotal + $shipping;

        $paymentMethod = $request->payment_method;

        // If Razorpay, create a Razorpay order and return to checkout for payment
        if ($paymentMethod === 'razorpay') {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt'  => 'ORD' . strtoupper(uniqid()),
                'amount'   => round($grandTotal * 100), // amount in paise
                'currency' => 'INR',
            ]);

            // Store order details in session for after payment verification
            session()->put('razorpay_order', [
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount'            => $grandTotal,
                'full_name'         => $request->full_name,
                'phone'             => $request->phone,
                'address'           => $request->address,
                'city'              => $request->city,
                'pincode'           => $request->pincode,
                'subtotal'          => $subtotal,
                'shipping'          => $shipping,
            ]);

            return view('customer.razorpay-payment', [
                'razorpayOrderId' => $razorpayOrder['id'],
                'amount'          => round($grandTotal * 100),
                'currency'        => 'INR',
                'keyId'           => config('services.razorpay.key'),
                'userName'        => $request->full_name,
                'userEmail'       => Auth::user()->email,
                'userPhone'       => $request->phone,
            ]);
        }

        // COD flow
        $order = $this->createOrder($request->only('full_name', 'phone', 'address', 'city', 'pincode'), $subtotal, $shipping, $grandTotal, 'cod', 'unpaid');

        $this->createOrderItems($order, $cart);
        $this->deductStock($cart);
        session()->forget('cart');

        $this->sendNotifications($order);

        return redirect()->route('customer.orders')->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }

    public function razorpayVerify(Request $request)
    {
        $sessionOrder = session()->get('razorpay_order');

        if (!$sessionOrder) {
            return redirect()->route('customer.checkout')->with('error', 'Payment session expired. Please try again.');
        }

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay payment verification failed: ' . $e->getMessage());
            session()->forget('razorpay_order');
            return redirect()->route('customer.checkout')->with('error', 'Payment verification failed. Please try again.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            session()->forget('razorpay_order');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $order = $this->createOrder(
            [
                'full_name' => $sessionOrder['full_name'],
                'phone'     => $sessionOrder['phone'],
                'address'   => $sessionOrder['address'],
                'city'      => $sessionOrder['city'],
                'pincode'   => $sessionOrder['pincode'],
            ],
            $sessionOrder['subtotal'],
            $sessionOrder['shipping'],
            $sessionOrder['amount'],
            'razorpay',
            'paid'
        );

        // Store Razorpay payment ID on the order
        $order->update(['razorpay_payment_id' => $request->razorpay_payment_id]);

        $this->createOrderItems($order, $cart);
        $this->deductStock($cart);

        session()->forget('cart');
        session()->forget('razorpay_order');

        $this->sendNotifications($order);

        return redirect()->route('customer.orders')->with('success', 'Payment successful! Order #' . $order->order_number);
    }

    public function razorpayCancel()
    {
        session()->forget('razorpay_order');
        return redirect()->route('customer.checkout')->with('error', 'Payment was cancelled.');
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();
        return view('customer.orders', compact('orders'));
    }

    public function cancel(Request $request, Order $order, OrderCancellationService $service)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:500',
        ]);

        if (!$order->canBeCancelled()) {
            return redirect()->route('customer.orders')
                ->with('error', 'This order can no longer be cancelled.');
        }

        try {
            $service->cancel($order, $data['cancellation_reason'], 'customer');
        } catch (\DomainException $e) {
            return redirect()->route('customer.orders')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Customer cancel failed: ' . $e->getMessage(), ['order' => $order->order_number]);
            return redirect()->route('customer.orders')
                ->with('error', 'Something went wrong cancelling the order.');
        }

        return redirect()->route('customer.orders')
            ->with('success', "Order #{$order->order_number} cancelled.");
    }

    // --- Helper methods ---

    private function createOrder(array $details, $subtotal, $shipping, $grandTotal, $paymentMethod, $paymentStatus)
    {
        return Order::create([
            'user_id'        => Auth::id(),
            'order_number'   => 'ORD' . strtoupper(uniqid()),
            'full_name'      => $details['full_name'],
            'phone'          => $details['phone'],
            'address'        => $details['address'],
            'city'           => $details['city'],
            'pincode'        => $details['pincode'],
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'grand_total'    => $grandTotal,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
        ]);
    }

    private function createOrderItems(Order $order, array $cart)
    {
        foreach ($cart as $productId => $item) {
            $order->items()->create([
                'product_id'      => $productId,
                'product_name'    => $item['name'],
                'price'           => $item['sale_price'] ?? $item['price'],
                'quantity'        => $item['quantity'],
                'shipping_charge' => $item['shipping_charge'] ?? 0,
            ]);
        }
    }

    private function deductStock(array $cart)
    {
        foreach ($cart as $productId => $item) {
            Product::where('id', $productId)
                ->where('stock', '>=', $item['quantity'])
                ->decrement('stock', $item['quantity']);
        }
    }

    private function sendNotifications(Order $order)
    {
        $order->load('items');

        try {
            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order));
        } catch (\Throwable $e) {
            Log::error('Order email failed: ' . $e->getMessage(), ['order' => $order->order_number]);
        }

        try {
            $sms     = app(SmsService::class);
            $message = "Hi {$order->full_name}, your order #{$order->order_number} has been placed successfully! Total: Rs.{$order->grand_total}. Thank you for shopping with Ekka_Lv.";
            $sms->send($order->phone, $message);
        } catch (\Throwable $e) {
            Log::error('Order SMS failed: ' . $e->getMessage(), ['order' => $order->order_number]);
        }
    }
}
