<?php

use App\Models\Review;
use App\Models\Stock;
use App\Models\Notification;
use App\Services\StockService;
use App\Services\CouponService;
use App\Services\PaymentService;
use App\Services\RefundService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

if (!function_exists('getProductRating')) {
    function getProductRating($productId)
    {
        return Review::where('product_id', $productId)->avg('rating') ?? 0;
    }
}

if (!function_exists('getProductReviewCount')) {
    function getProductReviewCount($productId)
    {
        return Review::where('product_id', $productId)->count();
    }
}

if (!function_exists('isProductInStock')) {
    function isProductInStock($productId)
    {
        $stock = Stock::where('product_id', $productId)->first();
        return $stock && $stock->getAvailableQuantity() > 0;
    }
}

if (!function_exists('getAvailableQuantity')) {
    function getAvailableQuantity($productId)
    {
        $stock = Stock::where('product_id', $productId)->first();
        return $stock ? $stock->getAvailableQuantity() : 0;
    }
}

if (!function_exists('notifyUser')) {
    function notifyUser($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

if (!function_exists('stockService')) {
    function stockService()
    {
        return app(StockService::class);
    }
}

if (!function_exists('couponService')) {
    function couponService()
    {
        return app(CouponService::class);
    }
}

if (!function_exists('paymentService')) {
    function paymentService()
    {
        return app(PaymentService::class);
    }
}

if (!function_exists('refundService')) {
    function refundService()
    {
        return app(RefundService::class);
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($amount, $currency = 'INR')
    {
        $symbols = [
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('canReturnOrder')) {
    function canReturnOrder($orderId)
    {
        $order = \App\Models\Order::find($orderId);

        if (!$order || !in_array($order->status, ['delivered', 'completed'])) {
            return false;
        }

        $returnWindow = now()->subDays(30);
        return $order->created_at >= $returnWindow;
    }
}

if (!function_exists('getUnreadNotifications')) {
    function getUnreadNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}

if (!function_exists('notifyOrderStatusChange')) {
    function notifyOrderStatusChange($orderId, $newStatus)
    {
        $order = \App\Models\Order::find($orderId);

        if (!$order) {
            return null;
        }

        $messages = [
            'pending' => 'Your order is pending confirmation',
            'processing' => 'Your order is being processed',
            'shipped' => 'Your order has been shipped',
            'arriving' => 'Your order is arriving today',
            'delivered' => 'Your order has been delivered',
            'completed' => 'Your order is complete',
            'cancelled' => 'Your order has been cancelled',
        ];

        $message = $messages[$newStatus] ?? "Order status updated to {$newStatus}";

        return notifyUser(
            $order->user_id,
            "order_{$newStatus}",
            'Order Status Updated',
            $message,
            ['order_id' => $orderId]
        );
    }
}

if (!function_exists('calculateOrderTotal')) {
    function calculateOrderTotal($subtotal, $shippingCharge, $discountAmount = 0)
    {
        return $subtotal + $shippingCharge - $discountAmount;
    }
}

if (!function_exists('getLowStockProducts')) {
    function getLowStockProducts($limit = 10)
    {
        return Stock::where('quantity', '<=', DB::raw('min_stock_level'))
            ->with('product')
            ->limit($limit)
            ->get();
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber()
    {
        $timestamp = now()->format('YmdHis');
        $random = mt_rand(1000, 9999);
        return 'ORD' . $timestamp . $random;
    }
}

if (!function_exists('generateTicketNumber')) {
    function generateTicketNumber()
    {
        $random = strtoupper(Str::random(8));
        $number = mt_rand(100000, 999999);
        return "TKT-{$random}-{$number}";
    }
}
