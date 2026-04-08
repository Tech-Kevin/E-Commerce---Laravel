<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessSales extends Command
{
    protected $signature = 'sales:process';
    protected $description = 'Activate scheduled sales and expire ended sales, updating product sale prices accordingly';

    public function handle(): void
    {
        $now = Carbon::now();

        // Activate scheduled sales whose start_date has passed
        $salesToActivate = Sale::where('status', 'scheduled')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now)
            ->get();

        foreach ($salesToActivate as $sale) {
            $sale->update(['status' => 'active']);
            $sale->product->update(['sale_price' => $sale->sale_price]);
        }

        if ($salesToActivate->count()) {
            $this->info("Activated {$salesToActivate->count()} sale(s).");
        }

        // Expire active sales whose end_date has passed
        $salesToExpire = Sale::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->get();

        foreach ($salesToExpire as $sale) {
            $sale->update(['status' => 'expired']);

            // Only clear sale_price if no other active sale exists for this product
            $hasOtherActiveSale = Sale::where('product_id', $sale->product_id)
                ->where('id', '!=', $sale->id)
                ->where('status', 'active')
                ->exists();

            if (!$hasOtherActiveSale) {
                $sale->product->update(['sale_price' => null]);
            }
        }

        if ($salesToExpire->count()) {
            $this->info("Expired {$salesToExpire->count()} sale(s).");
        }

        if ($salesToActivate->isEmpty() && $salesToExpire->isEmpty()) {
            $this->info('No sales to process.');
        }
    }
}
