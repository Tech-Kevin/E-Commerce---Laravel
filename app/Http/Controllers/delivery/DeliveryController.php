<?php

namespace App\Http\Controllers\delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOtp;
use App\Models\Order;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveryBoyId = Auth::id();

        $totalAssigned  = Order::where('delivery_boy_id', $deliveryBoyId)->whereNotIn('status', ['delivered', 'cancelled'])->count();
        $totalDelivered = Order::where('delivery_boy_id', $deliveryBoyId)->where('status', 'delivered')->count();
        $totalPickedUp  = Order::where('delivery_boy_id', $deliveryBoyId)->where('status', 'picked_up')->count();
        $totalOnTheWay  = Order::where('delivery_boy_id', $deliveryBoyId)->where('status', 'on_the_way')->count();
        $totalCompleted = Order::where('delivery_boy_id', $deliveryBoyId)->where('status', 'completed')->count();

        $recentOrders = Order::with(['user', 'items'])
            ->where('delivery_boy_id', $deliveryBoyId)
            ->latest()
            ->take(5)
            ->get();

        return view('delivery.dashboard', compact(
            'totalAssigned', 'totalDelivered', 'totalPickedUp', 'totalOnTheWay', 'totalCompleted', 'recentOrders'
        ));
    }

    public function assignedOrders()
    {
        $orders = Order::with(['user', 'items'])
            ->where('delivery_boy_id', Auth::id())
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()
            ->get();

        return view('delivery.orders', compact('orders'));
    }

    public function completedOrders()
    {
        $orders = Order::with(['user', 'items'])
            ->where('delivery_boy_id', Auth::id())
            ->where('status', 'delivered')
            ->latest()
            ->get();

        return view('delivery.completed', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:picked_up,on_the_way,completed',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    public function showVerifyPage(Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return redirect()->route('delivery.orders')->with('error', 'Unauthorized access.');
        }

        if ($order->status !== 'completed') {
            return redirect()->route('delivery.orders')->with('error', 'Order must be marked as completed to verify delivery.');
        }

        return view('delivery.verify', compact('order'));
    }

    public function sendOtp(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DeliveryOtp::where('order_id', $order->id)->where('is_verified', false)->delete();

        DeliveryOtp::create([
            'order_id'   => $order->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        $phone = $order->phone;
        $message = "Your delivery OTP for order #{$order->order_number} is: {$otp}. Valid for 10 minutes. Do not share this with anyone.";

        $smsService = new SmsService();
        $sent = $smsService->send($phone, $message);

        return response()->json([
            'success' => true,
            'message' => $sent ? 'OTP sent to registered number.' : 'OTP generated (SMS service unavailable). OTP: ' . $otp,
        ]);
    }

    public function verifyOtp(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $deliveryOtp = DeliveryOtp::where('order_id', $order->id)
            ->where('otp', $request->otp)
            ->where('is_verified', false)
            ->first();

        if (!$deliveryOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
        }

        if ($deliveryOtp->isExpired()) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
        }

        $deliveryOtp->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified. Please collect customer signature.',
        ]);
    }

    public function confirmDelivery(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $verifiedOtp = DeliveryOtp::where('order_id', $order->id)
            ->where('is_verified', true)
            ->first();

        if (!$verifiedOtp) {
            return response()->json(['success' => false, 'message' => 'OTP not verified yet.']);
        }

        // Save signature as image file
        $signatureData = $request->signature;
        $fileName = 'signature_' . $order->order_number . '_' . time() . '.png';
        $filePath = public_path('signatures/' . $fileName);

        if (!file_exists(public_path('signatures'))) {
            mkdir(public_path('signatures'), 0755, true);
        }

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));
        file_put_contents($filePath, $imageData);

        $order->update([
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order delivered successfully!',
        ]);
    }

    public function settings()
    {
        $user = Auth::user();
        return view('delivery.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:255',
            'number'  => 'nullable|string|max:15',
        ]);

        Auth::user()->update($request->only('name', 'email', 'address', 'number'));

        return redirect()->route('delivery.settings')->with('success', 'Settings saved successfully.');
    }
}
