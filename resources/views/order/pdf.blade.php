<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>🧾 Frejie's Cafe Sales Report</h2>
    <p>Date Range: {{ $start }} to {{ $end }}</p>

    <h4>Summary</h4>
    <p><strong>Total Sales:</strong> ₱{{ number_format($totalSales, 2) }}</p>

    <h4>Top-Selling Items</h4>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty Sold</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topItems as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->total_qty }}</td>
                <td>₱{{ number_format($item->total_sales, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="margin-top: 30px;">Order Details</h4>
    @foreach($orders as $order)
        <p><strong>Order #{{ $order->id }}</strong> — {{ $order->customer_name }} | Table: {{ $order->table->number ?? 'N/A' }} | ₱{{ number_format($order->total_amount, 2) }}</p>
        <ul>
            @foreach($order->orderItems as $item)
                <li>{{ $item->name }} — {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</li>
            @endforeach
        </ul>
    @endforeach
</body>
</html>
