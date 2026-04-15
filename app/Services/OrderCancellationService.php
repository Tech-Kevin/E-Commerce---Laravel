<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderCancellationService
{
    public function cancel(Order $order, string $reason, string $cancelledBy): Order
    {
        if (!in_array($cancelledBy, ['customer', 'vendor', 'system'], true)) {
            throw new \InvalidArgumentException('Invalid cancelledBy value');
        }

        if (!$order->canBeCancelled()) {
            throw new \DomainException("Order cannot be cancelled in status: {$order->status}");
        }

        return DB::transaction(function () use ($order, $reason, $cancelledBy) {
            $wasPaid = $order->payment_status === 'paid';
            $alreadyShipped = $order->status === 'shipped';

            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
                'cancelled_by' => $cancelledBy,
                'payment_status' => $wasPaid ? 'refund_pending' : $order->payment_status,
            ]);

            if (!$alreadyShipped) {
                $this->restoreStock($order);
            }

            $this->notifyCustomer($order, $reason, $cancelledBy);

            return $order->fresh();
        });
    }

    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
        }
    }

    private function notifyCustomer(Order $order, string $reason, string $cancelledBy): void
    {
        try {
            if (function_exists('notifyUser') && $order->user_id) {
                notifyUser(
                    $order->user_id,
                    'order_cancelled',
                    'Order Cancelled',
                    "Your order #{$order->order_number} has been cancelled. Reason: {$reason}",
                    [
                        'order_id' => $order->id,
                        'cancelled_by' => $cancelledBy,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Cancellation notification failed: ' . $e->getMessage(), [
                'order' => $order->order_number,
            ]);
        }
    }
}
