<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

<div class="unavailable-container text-center">

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
   <a href="{{ route('table.view', ['id' => $tableId]) }}" class="btn btn-primary">
    ⬅️ Back to Menu
  </a>

  @if($order)
    <div class="order-box" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
      <h3>🧾 Order #{{ $order->id }}</h3>
      <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
      <p><strong>Table:</strong> {{ $order->table->number ?? 'N/A' }}</p>
      <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

      <ul class="text-start">
        @foreach($order->orderItems as $item)
          <li>
            🍽️ {{ $item->name }} — {{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}
          </li>
        @endforeach
      </ul>
    </div>
  @else
    <p>No orders found.</p>
  @endif

</div>

</body>
</html>