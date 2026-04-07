<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #2f241f;
            margin: 0;
            padding: 0;
            background: #f9f3ee;
        }

        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .08);
        }

        .header {
            background: #2f241f;
            color: #fff;
            padding: 32px 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 14px;
            opacity: .8;
        }

        .body {
            padding: 32px 40px;
        }

        .body p {
            font-size: 15px;
            line-height: 1.7;
        }

        .highlight {
            color: #e05a2b;
            font-weight: 700;
        }

        .info-box {
            background: #f9f3ee;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 4px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th {
            background: #2f241f;
            color: #fff;
            padding: 10px 12px;
            font-size: 13px;
            text-align: left;
        }

        td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f2e7dc;
        }

        .totals td {
            font-weight: 600;
        }

        .totals .grand td {
            font-size: 15px;
            color: #e05a2b;
        }

        .footer {
            background: #f9f3ee;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #8a7769;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h1>Ekka_Lv</h1>
            <p>Order Confirmation</p>
        </div>

        <div class="body">
            <p>Hi <strong>{{ $order->full_name }}</strong>,</p>
            <p>
                Thank you for your order! We've received it and it's now being processed.
                Your order number is <span class="highlight">#{{ $order->order_number }}</span>.
            </p>
            <p>Please find your invoice attached to this email as a PDF.</p>

            <div class="info-box">
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->address }}, {{ $order->city }} – {{ $order->pincode }}
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td>₹ {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="totals">
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td>₹ {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Shipping</td>
                        <td>₹ {{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    <tr class="grand">
                        <td colspan="3">Grand Total</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <p style="margin-top:24px;">
                We'll notify you once your order is shipped. If you have any questions, reply to this email.
            </p>
            <p>Thanks for shopping with us!</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Ekka_Lv. All rights reserved.
        </div>
    </div>
</body>

</html>