<x-app-layout>
  <div class="container mt-4">
    <h2>📋 Accepted Orders</h2>

    @if($orders->isEmpty())
      <div class="alert alert-info mt-3">No accepted orders at the moment.</div>
    @endif

    @foreach($orders as $order)
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="mb-2">🧾 Order #{{ $order->id }}</h5>
          <p class="mb-1"><strong>Customer:</strong> {{ $order->customer_name }}</p>
          <p class="mb-1"><strong>Table:</strong> {{ $order->table->number ?? 'N/A' }}</p>
          <p class="mb-2"><span class="badge bg-warning">Accepted</span></p>

          <ul class="list-group mb-2">
            @foreach($order->orderItems as $item)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $item->quantity }} x {{ $item->name }}
                <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
              </li>
            @endforeach
          </ul>

          <p class="fw-bold">Total: ₱{{ number_format($order->total_amount, 2) }}</p>
        </div>
      </div>
      <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="row g-2 align-items-center">
  @csrf
  <div class="col-auto">
    <div class="input-group input-group-sm">
      <label class="input-group-text bg-light" for="statusSelect{{ $order->id }}">Status</label>
      <select name="status" id="statusSelect{{ $order->id }}" class="form-select">
       
        <option value="accept" {{ $order->status === 'accept' ? 'selected' : '' }}>Accept</option>
        <option value="served" {{ $order->status === 'served' ? 'selected' : '' }}>Served</option>
      </select>
    </div>
  </div>
  <div class="col-auto">
    <button class="btn btn-success btn-sm">
      <i class="bi bi-check2-circle"></i> Update
    </button>
  </div>
</form>
    @endforeach
  </div>
</x-app-layout>
