<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;

class PaymentService
{
    /**
     * Create a payment record
     */
    public function create($orderId, $amount, $paymentMethod, $transactionId, $data = null)
    {
        return Payment::create([
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => 'INR',
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'gateway_response' => $data,
        ]);
    }

    /**
     * Mark payment as completed
     */
    public function markCompleted($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update order payment status
        if ($payment->order) {
            $payment->order->update(['payment_status' => 'paid']);
        }

        return $payment;
    }

    /**
     * Mark payment as failed
     */
    public function markFailed($paymentId, $reason = null)
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->update([
            'status' => 'failed',
            'gateway_response' => $payment->gateway_response ? array_merge($payment->gateway_response, ['failure_reason' => $reason]) : ['failure_reason' => $reason],
        ]);

        // Update order payment status
        if ($payment->order) {
            $payment->order->update(['payment_status' => 'failed']);
        }

        return $payment;
    }

    /**
     * Refund payment
     */
    public function refund($paymentId, $amount = null)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'completed') {
            throw new \Exception('Can only refund completed payments');
        }

        $refundAmount = $amount ?? $payment->amount;

        $payment->update([
            'status' => 'refunded',
            'gateway_response' => $payment->gateway_response ? array_merge($payment->gateway_response, ['refunded_amount' => $refundAmount]) : ['refunded_amount' => $refundAmount],
        ]);

        // Update order payment status
        if ($payment->order) {
            $payment->order->update(['payment_status' => 'refunded']);
        }

        return $payment;
    }

    /**
     * Get payment by transaction ID
     */
    public function getByTransactionId($transactionId)
    {
        return Payment::where('transaction_id', $transactionId)->first();
    }

    /**
     * Get order payments
     */
    public function getOrderPayments($orderId)
    {
        return Payment::where('order_id', $orderId)->get();
    }
}
