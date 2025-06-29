<x-app-layout>
  <div class="container">

  {{-- Filter Form --}}
  <form method="GET" action="{{ route('order.sales') }}" onsubmit="return validateSalesFilter()" class="mb-4">
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label for="startDate" class="form-label">Start Date:</label>
      <input type="date" id="startDate" name="start_date" class="form-control"
        value="{{ $start }}" max="{{ now()->toDateString() }}">
    </div>

    <div class="col-md-4">
      <label for="endDate" class="form-label">End Date:</label>
      <input type="date" id="endDate" name="end_date" class="form-control"
        value="{{ $end }}" max="{{ now()->toDateString() }}">
    </div>

    <div class="col-md-4">
      <button type="submit" class="btn btn-primary w-100"> Filter Sales</button>
    </div>
    <a href="{{ route('sales.export.pdf', ['start_date' => $start, 'end_date' => $end]) }}"
   class="btn btn-outline-danger ms-2">
   🖨️ Export Served Sales PDF
</a>

  </div>
  </form>


  <div class="text-start mt-3">
    <h5> Total Sales: ₱{{ number_format($totalSales, 2) }}</h5>
  </div>

  <h4 class="mt-5"> Top-Selling Items</h4>

  <table class="table table-bordered table-striped mt-2">
    <thead class="table-dark">
      <tr>
        <th>Item</th>
        <th>Qty Sold</th>
        <th>Total Sales</th>
      </tr>
    </thead>
    <tbody>
      @forelse($topItems as $item)
        <tr>
          <td>{{ $item->name }}</td>
          <td>{{ $item->total_qty }}</td>
          <td>₱{{ number_format($item->total_sales, 2) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="text-center">No sales data found for this period.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  </div>

  <script>
  function validateSalesFilter() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const today = new Date().toISOString().split('T')[0];

    // Start date required
    if (!startDate) {
      alert('Please select a start date.');
      return false;
    }

    if (startDate > today) {
      alert(" Start date cannot be in the future.");
      return false;
    }

    // If end date is selected, check that it's not before start date
    if (endDate && endDate < startDate) {
      alert(" End Date cannot be earlier than Start Date.");
      return false;
    }

    return true;
  }
  </script>
</x-app-layout>
