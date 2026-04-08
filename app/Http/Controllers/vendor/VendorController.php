<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue  = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $recentOrders  = Order::with(['user', 'items'])->latest()->take(8)->get();
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
            ->withSum('orders', 'grand_total')
            ->latest()
            ->get();
        return view('vendor.customer', compact('customers'));
    }

    public function ShowAnalytics()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $lastMonth = $now->copy()->subMonth();

        // --- Stats Cards ---
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $totalOrders = Order::count();
        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Revenue growth: current month vs last month
        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('grand_total');
        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('grand_total');
        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // Orders this week vs last week
        $ordersThisWeek = Order::whereBetween('created_at', [$now->copy()->startOfWeek(), $now])->count();
        $ordersLastWeek = Order::whereBetween('created_at', [
            $now->copy()->subWeek()->startOfWeek(),
            $now->copy()->subWeek()->endOfWeek()
        ])->count();
        $ordersGrowth = $ordersLastWeek > 0
            ? round((($ordersThisWeek - $ordersLastWeek) / $ordersLastWeek) * 100, 1)
            : ($ordersThisWeek > 0 ? 100 : 0);

        // Customer growth
        $customersLastMonth = User::where('role', 'customer')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $customerGrowth = $customersLastMonth > 0
            ? round((($newCustomersThisMonth - $customersLastMonth) / $customersLastMonth) * 100, 1)
            : ($newCustomersThisMonth > 0 ? 100 : 0);

        // Average order value
        $avgOrderValue = Order::where('status', '!=', 'cancelled')->avg('grand_total') ?? 0;

        // --- Monthly Revenue (for chart) ---
        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        $revenueData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueData[] = round($monthlyRevenue[$m] ?? 0, 2);
        }

        // --- Order Status Distribution (replaces Traffic Sources) ---
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // --- Daily Orders & New Customers (last 7 days) ---
        $dailyLabels = [];
        $dailyOrders = [];
        $dailyCustomers = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $dailyLabels[] = $day->format('D');
            $dailyOrders[] = Order::whereDate('created_at', $day->toDateString())->count();
            $dailyCustomers[] = User::where('role', 'customer')
                ->whereDate('created_at', $day->toDateString())
                ->count();
        }

        // --- Top Products this month ---
        $topProducts = OrderItem::select('product_id', 'product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(price * quantity) as total_revenue')
            ->whereHas('order', function ($q) use ($currentMonth, $currentYear) {
                $q->where('status', '!=', 'cancelled')
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // --- Recent Activity (last 5 orders) ---
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('vendor.analytics', compact(
            'totalRevenue', 'totalOrders', 'newCustomersThisMonth', 'avgOrderValue',
            'revenueGrowth', 'ordersGrowth', 'customerGrowth', 'revenueThisMonth',
            'revenueData', 'statusCounts',
            'dailyLabels', 'dailyOrders', 'dailyCustomers',
            'topProducts', 'recentOrders'
        ));
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
