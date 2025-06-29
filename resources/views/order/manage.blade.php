<x-app-layout>
  <div class="container mt-4">
    <h2 class="mb-4">🧾 Manage Orders</h2>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter Dropdown -->
  <form method="GET" class="mb-4 p-3 rounded bg-light shadow-sm d-flex align-items-center gap-3">
  <label for="status" class="fw-semibold mb-0">
    <i class="bi bi-funnel-fill me-1"></i> Filter by Status:
  </label>

  <select name="status" id="status" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
    <option value="">All</option>
    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
    <option value="accept" {{ request('status') == 'accept' ? 'selected' : '' }}>Accepted</option>
    <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Served</option>
  </select>
</form>


    @forelse($orders as $order)
      @php
        $statusColors = [
          'pending' => 'secondary',
          'accept' => 'warning',
          'served' => 'success',
        ];
      @endphp

      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">🧾 Order #{{ $order->id }}</h5>
            <span class="badge bg-{{ $statusColors[$order->status] ?? 'light' }}">
              {{ ucfirst($order->status) }}
            </span>
          </div>

          <p class="mb-1"><strong>Customer:</strong> {{ $order->customer_name }}</p>
          <p class="mb-1"><strong>Table:</strong> {{ $order->table->number }} <b>{{ $order->table->name}}</b></p>

          <ul class="list-group mb-3">
         @foreach($order->orderItems as $item)
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <div>{{ $item->quantity }} x {{ $item->name }}</div>
    <div class="d-flex align-items-center gap-2">
      <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>

      <!-- Trigger Button -->
      <button type="button" class="btn btn-sm btn-outline-danger"
              data-bs-toggle="modal"
              data-bs-target="#cancelItemModal{{ $item->id }}">
        <i class="bi bi-x-circle"></i>
      </button>
    </div>
  </li>

  <!-- Modal -->
  <div class="modal fade" id="cancelItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="cancelItemModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="cancelItemModalLabel{{ $item->id }}">Cancel Item</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to cancel <strong>{{ $item->quantity }} x {{ $item->name }}</strong>?
        </div>
        <div class="modal-footer">
            <form action="{{ route('order-items.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
            <button type="submit" class="btn btn-danger">Yes, Cancel</button>
          </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss=
          "modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

          </ul>

          <p><strong>Total:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
          

          <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="row g-2 align-items-center">
  @csrf
  <div class="col-auto">
    <div class="input-group input-group-sm">
      <label class="input-group-text bg-light" for="statusSelect{{ $order->id }}">Status</label>
      <select name="status" id="statusSelect{{ $order->id }}" class="form-select">
        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
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
        </div>
    @empty
      <div class="alert alert-info">No orders found.</div>
    @endforelse
</form>

</x-app-layout>

