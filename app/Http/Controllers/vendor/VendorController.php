<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue  = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $recentOrders  = Order::with(['user', 'items'])->latest()->take(5)->get();
        $topProducts   = Product::withCount('orders')->orderByDesc('orders_count')->take(5)->get();

        return view('vendor.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCustomers',
            'totalRevenue', 'recentOrders', 'topProducts'
        ));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'arriving', 'delivered', 'cancelled'])],
        ]);

        $previousStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Deduct stock when status changes to 'shipped' (only once)
        if ($newStatus === 'shipped' && $previousStatus !== 'shipped') {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->where('stock', '>', 0)
                    ->decrement('stock', $item->quantity);
            }
        }

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    public function assignDeliveryBoy(Request $request, Order $order)
    {
        $request->validate([
            'delivery_boy_id' => 'required|exists:users,id',
        ]);

        $deliveryBoy = User::where('id', $request->delivery_boy_id)->where('role', 'delivery')->firstOrFail();

        $order->update(['delivery_boy_id' => $deliveryBoy->id]);

        return response()->json([
            'success' => true,
            'delivery_boy_name' => $deliveryBoy->name,
        ]);
    }

    public function ShowOrders()
    {
        $orders = Order::with(['user', 'items', 'deliveryBoy'])->latest()->get();
        $deliveryBoys = User::where('role', 'delivery')->get();
        return view('vendor.order', compact('orders', 'deliveryBoys'));
    }

    public function ShowCustomers()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->get();
        return view('vendor.customer', compact('customers'));
    }

    public function ShowAnalytics()
    {
        return view('vendor.analytics');
    }

    public function ShowEarnings()
    {
        return view('vendor.earnings');
    }

    public function ShowSettings()
    {
        $user = Auth::user();
        return view('vendor.settings', compact('user'));
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

        return redirect()->route('vendor.settings')->with('success', 'Settings saved successfully.');
    }
}
