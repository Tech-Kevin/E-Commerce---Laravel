<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    /* =====================================================
     | USER CREATE + PASSWORD RESET
     ===================================================== */

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => ['required', Rule::in(['admin', 'vendor', 'delivery', 'customer'])],
            'number'   => 'nullable|string|max:15',
            'address'  => 'nullable|string|max:255',
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'number'    => $data['number'] ?? null,
            'address'   => $data['address'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('vendor.users.index')->with('success', 'User created successfully.');
    }

    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        // Force logout of that user's sessions for safety
        DB::table('sessions')->where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => "Password reset for {$user->name}.",
        ]);
    }

    /* =====================================================
     | IMPERSONATION
     ===================================================== */

    public function impersonate(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You are already this user.');
        }

        // Stash the original super-admin id
        session(['impersonator_id' => Auth::id()]);

        Auth::login($user);

        return match ($user->role) {
            'vendor', 'admin' => redirect()->route('vendor.dashboard')->with('success', "You are now viewing as {$user->name}"),
            'delivery'        => redirect()->route('delivery.dashboard')->with('success', "You are now viewing as {$user->name}"),
            default           => redirect()->route('home')->with('success', "You are now viewing as {$user->name}"),
        };
    }

    public function stopImpersonate(Request $request)
    {
        $originalId = session('impersonator_id');

        if (!$originalId) {
            return redirect()->route('vendor.dashboard');
        }

        $original = User::find($originalId);
        if (!$original) {
            session()->forget('impersonator_id');
            return redirect()->route('loginForm');
        }

        Auth::login($original);
        session()->forget('impersonator_id');

        return redirect()->route('vendor.dashboard')->with('success', 'Returned to super admin.');
    }

    /* =====================================================
     | ORDER EDITING
     ===================================================== */

    public function editOrder(Order $order)
    {
        $order->load(['items', 'user', 'deliveryBoy']);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        $deliveryBoys = User::where('role', 'delivery')->where('is_active', true)->get(['id', 'name']);

        return view('vendor.orders.edit', compact('order', 'products', 'deliveryBoys'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate([
            'full_name'        => 'required|string|max:120',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'city'             => 'required|string|max:80',
            'pincode'          => 'required|string|max:20',
            'status'           => ['required', Rule::in(['pending','processing','shipped','arriving','delivered','cancelled','completed'])],
            'payment_method'   => 'nullable|string|max:40',
            'payment_status'   => ['required', Rule::in(['pending','paid','refunded','failed'])],
            'shipping'         => 'required|numeric|min:0',
            'delivery_boy_id'  => 'nullable|exists:users,id',
            'items'            => 'array',
            'items.*.id'       => 'nullable|integer|exists:order_items,id',
            'items.*.product_id' => 'nullable|integer|exists:products,id',
            'items.*.product_name' => 'nullable|string|max:180',
            'items.*.price'    => 'nullable|numeric|min:0',
            'items.*.quantity' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($data, $order, $request) {
            $order->fill([
                'full_name'       => $data['full_name'],
                'phone'           => $data['phone'],
                'address'         => $data['address'],
                'city'            => $data['city'],
                'pincode'         => $data['pincode'],
                'status'          => $data['status'],
                'payment_method'  => $data['payment_method'] ?? $order->payment_method,
                'payment_status'  => $data['payment_status'],
                'shipping'        => $data['shipping'],
                'delivery_boy_id' => $data['delivery_boy_id'] ?? null,
            ]);

            $submittedIds = [];
            $subtotal = 0;

            foreach ($request->input('items', []) as $row) {
                if (empty($row['product_id']) || empty($row['price']) || empty($row['quantity'])) {
                    continue;
                }

                $price = (float) $row['price'];
                $qty   = (int)   $row['quantity'];
                $subtotal += $price * $qty;

                if (!empty($row['id'])) {
                    $item = OrderItem::where('order_id', $order->id)->find($row['id']);
                    if ($item) {
                        $item->update([
                            'product_id'   => $row['product_id'],
                            'product_name' => $row['product_name'] ?? $item->product_name,
                            'price'        => $price,
                            'quantity'     => $qty,
                        ]);
                        $submittedIds[] = $item->id;
                    }
                } else {
                    $new = $order->items()->create([
                        'product_id'   => $row['product_id'],
                        'product_name' => $row['product_name'] ?? optional(Product::find($row['product_id']))->name ?? 'Product',
                        'price'        => $price,
                        'quantity'     => $qty,
                    ]);
                    $submittedIds[] = $new->id;
                }
            }

            // Delete any items that were removed from the form
            OrderItem::where('order_id', $order->id)
                ->whereNotIn('id', $submittedIds)
                ->delete();

            $order->subtotal = $subtotal;
            $order->grand_total = $subtotal + (float) $data['shipping'];
            $order->save();
        });

        return redirect()->route('vendor.orders')->with('success', 'Order updated successfully.');
    }

    public function destroyOrder(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('vendor.orders')->with('success', 'Order deleted permanently.');
    }

    public function markOrderPaid(Order $order)
    {
        $order->update(['payment_status' => 'paid']);
        return response()->json(['success' => true]);
    }

    public function refundOrder(Order $order)
    {
        $order->update(['payment_status' => 'refunded', 'status' => 'cancelled']);
        return response()->json(['success' => true]);
    }

    /* =====================================================
     | PRODUCT BULK ACTIONS
     ===================================================== */

    public function bulkProducts(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['delete', 'stock', 'price_percent'])],
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:products,id',
            'value'  => 'nullable|numeric',
        ]);

        $products = Product::whereIn('id', $data['ids']);

        switch ($data['action']) {
            case 'delete':
                $products->delete();
                $msg = 'Products deleted.';
                break;

            case 'stock':
                $products->update(['stock' => (int) ($data['value'] ?? 0)]);
                $msg = 'Stock updated.';
                break;

            case 'price_percent':
                $percent = (float) ($data['value'] ?? 0);
                foreach ($products->get() as $p) {
                    $newPrice = max(0, round($p->price * (1 + $percent / 100), 2));
                    $p->update(['price' => $newPrice]);
                }
                $msg = "Prices adjusted by {$percent}%.";
                break;
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    /* =====================================================
     | SITE CONTENT / BRANDING
     ===================================================== */

    public function showContent()
    {
        $siteSetting = SiteSetting::getSettings();
        return view('vendor.site-content', compact('siteSetting'));
    }

    public function updateContent(Request $request)
    {
        $data = $request->validate([
            'store_name'       => 'required|string|max:100',
            'store_tagline'    => 'nullable|string|max:150',
            'logo'             => 'nullable|image|max:2048',
            'favicon'          => 'nullable|image|max:1024',
            'hero_image'       => 'nullable|image|max:4096',
            'hero_title'       => 'nullable|string|max:150',
            'hero_subtitle'    => 'nullable|string|max:250',
            'hero_cta_text'    => 'nullable|string|max:60',
            'hero_cta_url'     => 'nullable|string|max:255',
            'contact_email'    => 'nullable|email|max:150',
            'contact_phone'    => 'nullable|string|max:30',
            'contact_address'  => 'nullable|string|max:255',
            'facebook_url'     => 'nullable|string|max:255',
            'instagram_url'    => 'nullable|string|max:255',
            'twitter_url'      => 'nullable|string|max:255',
            'youtube_url'      => 'nullable|string|max:255',
            'footer_about'     => 'nullable|string|max:500',
            'footer_copyright' => 'nullable|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        $settings = SiteSetting::getSettings();

        foreach (['logo' => 'logo_path', 'favicon' => 'favicon_path', 'hero_image' => 'hero_image_path'] as $field => $column) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('site', 'public');
                $data[$column] = $path;
            }
        }

        $data['maintenance_mode'] = $request->boolean('maintenance_mode');

        $settings->update(collect($data)->except(['logo', 'favicon', 'hero_image'])->toArray());

        return redirect()->route('vendor.site.content')->with('success', 'Site content updated successfully.');
    }

    /* =====================================================
     | SYSTEM TOOLS
     ===================================================== */

    public function showSystem()
    {
        $logPath = storage_path('logs/laravel.log');
        $logTail = '';
        if (File::exists($logPath)) {
            $content = File::get($logPath);
            $logTail = implode("\n", array_slice(explode("\n", $content), -200));
        }

        $migrationStatus = '';
        try {
            Artisan::call('migrate:status');
            $migrationStatus = Artisan::output();
        } catch (\Throwable $e) {
            $migrationStatus = 'Unable to read migration status: ' . $e->getMessage();
        }

        $diskSize = 0;
        if (File::isDirectory(storage_path('app'))) {
            foreach (File::allFiles(storage_path('app')) as $f) {
                $diskSize += $f->getSize();
            }
        }

        return view('vendor.system.index', [
            'logTail' => $logTail,
            'migrationStatus' => $migrationStatus,
            'diskSizeMb' => round($diskSize / 1048576, 2),
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'env' => app()->environment(),
        ]);
    }

    public function runSystemAction(Request $request)
    {
        $action = $request->validate(['action' => 'required|string'])['action'];

        try {
            switch ($action) {
                case 'cache_clear':
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('route:clear');
                    $msg = 'All caches cleared.';
                    break;
                case 'optimize':
                    Artisan::call('optimize');
                    $msg = 'App optimized.';
                    break;
                case 'migrate':
                    Artisan::call('migrate', ['--force' => true]);
                    $msg = 'Migrations run.';
                    break;
                case 'storage_link':
                    Artisan::call('storage:link');
                    $msg = 'Storage linked.';
                    break;
                case 'clear_logs':
                    File::put(storage_path('logs/laravel.log'), '');
                    $msg = 'Log file cleared.';
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Unknown action.'], 422);
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => $msg, 'output' => Artisan::output()]);
    }
}
