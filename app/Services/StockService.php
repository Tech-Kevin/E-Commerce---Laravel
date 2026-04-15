<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\Product;

class StockService
{
    /**
     * Check if product has sufficient stock
     */
    public function hasStock($productId, $quantity = 1)
    {
        $stock = Stock::where('product_id', $productId)->first();

        if (!$stock) {
            return false;
        }

        return $stock->getAvailableQuantity() >= $quantity;
    }

    /**
     * Reserve stock for cart/pending order
     */
    public function reserve($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId)->first();

        if (!$stock || $stock->getAvailableQuantity() < $quantity) {
            throw new \Exception("Insufficient stock for product {$productId}");
        }

        $stock->update([
            'reserved_quantity' => $stock->reserved_quantity + $quantity,
        ]);

        return true;
    }

    /**
     * Release reserved stock
     */
    public function release($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId)->first();

        if ($stock) {
            $stock->update([
                'reserved_quantity' => max(0, $stock->reserved_quantity - $quantity),
            ]);
        }

        return true;
    }

    /**
     * Deduct stock when order is confirmed
     */
    public function deduct($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId)->firstOrFail();

        if ($stock->getAvailableQuantity() < $quantity) {
            throw new \Exception("Insufficient stock for product {$productId}");
        }

        $stock->update([
            'quantity' => $stock->quantity - $quantity,
            'reserved_quantity' => max(0, $stock->reserved_quantity - $quantity),
        ]);

        // Check low stock level
        if ($stock->isLowStock()) {
            // Emit low stock notification to vendor
            // event(new LowStockAlert($stock));
        }

        return true;
    }

    /**
     * Restore stock when order is cancelled
     */
    public function restore($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId)->first();

        if ($stock) {
            $stock->update([
                'quantity' => $stock->quantity + $quantity,
                'reserved_quantity' => max(0, $stock->reserved_quantity - $quantity),
            ]);
        }

        return true;
    }

    /**
     * Update stock level
     */
    public function updateStock($productId, $newQuantity, $minLevel = 10)
    {
        Stock::updateOrCreate(
            ['product_id' => $productId],
            [
                'quantity' => $newQuantity,
                'min_stock_level' => $minLevel,
                'last_restocked_at' => now(),
            ]
        );

        return true;
    }

    /**
     * Get current stock status
     */
    public function getStatus($productId)
    {
        $stock = Stock::where('product_id', $productId)->first();

        if (!$stock) {
            return ['status' => 'out_of_stock', 'available' => 0];
        }

        $available = $stock->getAvailableQuantity();

        if ($available <= 0) {
            return ['status' => 'out_of_stock', 'available' => 0];
        }

        if ($stock->isLowStock()) {
            return ['status' => 'low_stock', 'available' => $available];
        }

        return ['status' => 'in_stock', 'available' => $available];
    }
}
