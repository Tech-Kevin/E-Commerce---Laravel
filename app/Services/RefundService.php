<?php

namespace App\Services;

use App\Models\ProductReturn;
use App\Models\ReturnItem;
use App\Models\Notification;

class RefundService
{
    /**
     * Create return request
     */
    public function createReturn($orderId, $reason, $items = [])
    {
        $return = ProductReturn::create([
            'order_id' => $orderId,
            'reason' => $reason,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        // Add return items
        foreach ($items as $itemId => $quantity) {
            ReturnItem::create([
                'return_id' => $return->id,
                'order_item_id' => $itemId,
                'quantity' => $quantity,
            ]);
        }

        // Create notification
        $order = $return->order;
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'return_requested',
            'title' => 'Return Request Submitted',
            'message' => "Your return request for order #{$order->order_number} has been submitted",
            'data' => ['order_id' => $order->id, 'return_id' => $return->id],
        ]);

        return $return;
    }

    /**
     * Approve return request
     */
    public function approveReturn($returnId, $refundAmount = null)
    {
        $return = ProductReturn::findOrFail($returnId);

        if ($return->status !== 'requested') {
            throw new \Exception('Can only approve pending return requests');
        }

        $refundAmount = $refundAmount ?? $return->order->grand_total;

        $return->update([
            'status' => 'approved',
            'refund_amount' => $refundAmount,
            'approved_at' => now(),
        ]);

        // Create notification
        Notification::create([
            'user_id' => $return->order->user_id,
            'type' => 'return_approved',
            'title' => 'Return Approved',
            'message' => "Your return request has been approved. Refund amount: ₹{$refundAmount}",
            'data' => ['return_id' => $return->id],
        ]);

        return $return;
    }

    /**
     * Reject return request
     */
    public function rejectReturn($returnId, $reason)
    {
        $return = ProductReturn::findOrFail($returnId);

        if ($return->status !== 'requested') {
            throw new \Exception('Can only reject pending return requests');
        }

        $return->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $return->order->user_id,
            'type' => 'return_rejected',
            'title' => 'Return Request Rejected',
            'message' => "Your return request has been rejected. Reason: {$reason}",
            'data' => ['return_id' => $return->id],
        ]);

        return $return;
    }

    /**
     * Mark return as completed
     */
    public function completeReturn($returnId)
    {
        $return = ProductReturn::findOrFail($returnId);

        $return->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Create notification
        Notification::create([
            'user_id' => $return->order->user_id,
            'type' => 'return_completed',
            'title' => 'Return Completed',
            'message' => "Your return has been completed. Refund amount: ₹{$return->refund_amount}",
            'data' => ['return_id' => $return->id],
        ]);

        return $return;
    }

    /**
     * Get return status
     */
    public function getStatus($returnId)
    {
        return ProductReturn::findOrFail($returnId);
    }
}
