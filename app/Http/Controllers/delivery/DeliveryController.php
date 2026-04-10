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
            return response()->json(['success' => false, 'message' => __('delivery.unauthorized')], 403);
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
            return redirect()->route('delivery.orders')->with('error', __('delivery.unauthorized_access'));
        }

        if ($order->status !== 'completed') {
            return redirect()->route('delivery.orders')->with('error', __('delivery.must_complete_to_verify'));
        }

        return view('delivery.verify', compact('order'));
    }

    public function sendOtp(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => __('delivery.unauthorized')], 403);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DeliveryOtp::where('order_id', $order->id)->where('is_verified', false)->delete();

        DeliveryOtp::create([
            'order_id'   => $order->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        $phone = $order->phone;
        $message = __('delivery.sms_otp_message', ['number' => $order->order_number, 'otp' => $otp]);

        $smsService = new SmsService();
        $sent = $smsService->send($phone, $message);

        return response()->json([
            'success' => true,
            'message' => $sent
                ? __('delivery.otp_sent')
                : __('delivery.otp_generated_fallback', ['otp' => $otp]),
        ]);
    }

    public function verifyOtp(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => __('delivery.unauthorized')], 403);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $deliveryOtp = DeliveryOtp::where('order_id', $order->id)
            ->where('otp', $request->otp)
            ->where('is_verified', false)
            ->first();

        if (!$deliveryOtp) {
            return response()->json(['success' => false, 'message' => __('delivery.invalid_otp')]);
        }

        if ($deliveryOtp->isExpired()) {
            return response()->json(['success' => false, 'message' => __('delivery.otp_expired')]);
        }

        $deliveryOtp->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => __('delivery.otp_verified'),
        ]);
    }

    public function confirmDelivery(Request $request, Order $order)
    {
        if ($order->delivery_boy_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => __('delivery.unauthorized')], 403);
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $verifiedOtp = DeliveryOtp::where('order_id', $order->id)
            ->where('is_verified', true)
            ->first();

        if (!$verifiedOtp) {
            return response()->json(['success' => false, 'message' => __('delivery.otp_not_verified')]);
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
            'message' => __('delivery.order_delivered_success'),
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

        return redirect()->route('delivery.settings')->with('success', __('delivery.settings_saved'));
    }

    public function switchLanguage(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,hi',
        ]);

        Auth::user()->update(['locale' => $request->locale]);

        return redirect()->back()->with('success', __('delivery.language_changed'));
    }
}
