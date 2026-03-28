<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #2f241f;
            background: #fff;
        }

        .invoice-wrap {
            padding: 40px;
        }

        /* Header */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            border-bottom: 3px solid #2f241f;
            padding-bottom: 20px;
        }

        .brand h1 {
            font-size: 28px;
            color: #2f241f;
            letter-spacing: 1px;
        }

        .brand p {
            font-size: 12px;
            color: #8a7769;
            margin-top: 2px;
        }

        .inv-meta {
            text-align: right;
        }

        .inv-meta h2 {
            font-size: 22px;
            color: #e05a2b;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .inv-meta p {
            font-size: 12px;
            color: #6d5c53;
            margin-top: 4px;
        }

        /* Info row */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .info-block h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8a7769;
            margin-bottom: 6px;
        }

        .info-block p {
            font-size: 13px;
            line-height: 1.6;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            background: #f2e7dc;
            color: #2f241f;
        }

        /* Items table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead tr {
            background: #2f241f;
            color: #fff;
        }

        thead th {
            padding: 10px 12px;
            font-size: 12px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background: #faf5f0;
        }

        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f2e7dc;
        }

        /* Totals */
        .totals-wrap {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 260px;
        }

        .totals-table td {
            padding: 7px 12px;
            font-size: 13px;
        }

        .totals-table .grand-row td {
            font-size: 15px;
            font-weight: 700;
            color: #e05a2b;
            border-top: 2px solid #2f241f;
            padding-top: 10px;
        }

        /* Footer */
        .inv-footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #f2e7dc;
            text-align: center;
            font-size: 11px;
            color: #8a7769;
        }
    </style>
</head>

<body>
    <div class="invoice-wrap">

        <div class="inv-header">
            <div class="brand">
                <h1>Ekka_Lv</h1>
                <p>Your premium everyday essentials store</p>
            </div>
            <div class="inv-meta">
                <h2>Invoice</h2>
                <p><strong>#{{ $order->order_number }}</strong></p>
                <p>{{ $order->created_at->format('d M Y') }}</p>
                <p style="margin-top:6px;"><span class="status-badge">{{ ucfirst($order->status) }}</span></p>
            </div>
        </div>

        <div class="info-row">
            <div class="info-block">
                <h4>Billed To</h4>
                <p><strong>{{ $order->full_name }}</strong></p>
                <p>{{ $order->address }}</p>
                <p>{{ $order->city }} – {{ $order->pincode }}</p>
                <p>Phone: {{ $order->phone }}</p>
            </div>
            <div class="info-block" style="text-align:right;">
                <h4>Payment Info</h4>
                <p>Method: <strong>{{ strtoupper($order->payment_method) }}</strong></p>
                <p>Status: <strong>{{ ucfirst($order->payment_status ?? 'pending') }}</strong></p>
                <p>Date: {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Shipping</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->shipping_charge, 2) }}</td>
                        <td style="text-align:right;">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-wrap">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td style="text-align:right;">₹{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping Charges</td>
                    <td style="text-align:right;">₹{{ number_format($order->shipping, 2) }}</td>
                </tr>
                <tr class="grand-row">
                    <td>Grand Total</td>
                    <td style="text-align:right;">₹{{ number_format($order->grand_total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="inv-footer">
            <p>Thank you for shopping with Ekka_Lv!</p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
        </div>

    </div>
</body>

</html>