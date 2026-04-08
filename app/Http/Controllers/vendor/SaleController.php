<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->latest()->get();
        $products = Product::where('is_active', true)->get();
        return view('vendor.sales.index', compact('sales', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'sale_price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $now = Carbon::now();
        $startDate = Carbon::parse($request->start_date);
        $status = $startDate->lte($now) ? 'active' : 'scheduled';

        // "all" means apply to every active product
        if ($request->product_id === 'all') {
            $products = Product::where('is_active', true)->get();
            $created = 0;

            foreach ($products as $product) {
                if ($request->sale_price >= $product->price) {
                    continue; // skip products where sale_price >= regular price
                }

                // Check overlap
                $overlap = Sale::where('product_id', $product->id)
                    ->whereIn('status', ['scheduled', 'active'])
                    ->where(function ($q) use ($request) {
                        $q->where('start_date', '<', $request->end_date)
                          ->where('end_date', '>', $request->start_date);
                    })
                    ->exists();

                if ($overlap) {
                    continue;
                }

                Sale::create([
                    'product_id' => $product->id,
                    'sale_price' => $request->sale_price,
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                    'status'     => $status,
                ]);

                if ($status === 'active') {
                    $product->update(['sale_price' => $request->sale_price]);
                }

                $created++;
            }

            return redirect()->route('vendor.sales')->with('success', "Sale created for $created product(s).");
        }

        // Single product sale
        $product = Product::findOrFail($request->product_id);

        if ($request->sale_price >= $product->price) {
            return back()->with('error', 'Sale price must be less than the regular price (₹' . $product->price . ').');
        }

        $overlap = Sale::where('product_id', $request->product_id)
            ->whereIn('status', ['scheduled', 'active'])
            ->where(function ($q) use ($request) {
                $q->where('start_date', '<', $request->end_date)
                  ->where('end_date', '>', $request->start_date);
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'This product already has a sale scheduled during this time period.');
        }

        if ($status === 'active') {
            $product->update(['sale_price' => $request->sale_price]);
        }

        Sale::create([
            'product_id' => $request->product_id,
            'sale_price' => $request->sale_price,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $status,
        ]);

        return redirect()->route('vendor.sales')->with('success', 'Sale created successfully.');
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        if ($sale->status === 'expired') {
            return back()->with('error', 'Cannot edit an expired sale.');
        }

        $request->validate([
            'sale_price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $product = $sale->product;

        if ($request->sale_price >= $product->price) {
            return back()->with('error', 'Sale price must be less than the regular price (₹' . $product->price . ').');
        }

        // Check overlap excluding current sale
        $overlap = Sale::where('product_id', $sale->product_id)
            ->where('id', '!=', $sale->id)
            ->whereIn('status', ['scheduled', 'active'])
            ->where(function ($q) use ($request) {
                $q->where('start_date', '<', $request->end_date)
                  ->where('end_date', '>', $request->start_date);
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'This product already has another sale during this time period.');
        }

        $now = Carbon::now();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        if ($endDate->lte($now)) {
            $status = 'expired';
        } elseif ($startDate->lte($now)) {
            $status = 'active';
        } else {
            $status = 'scheduled';
        }

        // If sale was active but is now being rescheduled, clear product sale_price
        if ($sale->status === 'active' && $status !== 'active') {
            $hasOtherActiveSale = Sale::where('product_id', $sale->product_id)
                ->where('id', '!=', $sale->id)
                ->where('status', 'active')
                ->exists();

            if (!$hasOtherActiveSale) {
                $product->update(['sale_price' => null]);
            }
        }

        // If sale is now active, update product sale_price
        if ($status === 'active') {
            $product->update(['sale_price' => $request->sale_price]);
        }

        $sale->update([
            'sale_price' => $request->sale_price,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'status'     => $status,
        ]);

        return redirect()->route('vendor.sales')->with('success', 'Sale updated successfully.');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        if ($sale->status === 'active') {
            $hasOtherActiveSale = Sale::where('product_id', $sale->product_id)
                ->where('id', '!=', $sale->id)
                ->where('status', 'active')
                ->exists();

            if (!$hasOtherActiveSale) {
                $sale->product->update(['sale_price' => null]);
            }
        }

        $sale->delete();

        return redirect()->route('vendor.sales')->with('success', 'Sale deleted successfully.');
    }
}
