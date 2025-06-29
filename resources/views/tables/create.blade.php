<x-app-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Manage Tables</h2>
            @if (Auth::user()->role === 1)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTableModal">
                <i class="bi bi-plus-circle"></i> Add Table
            </button>
            @endif
        </div>

        @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {!! session('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {!! session('error') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

        <div class="row">
            @forelse ($tables as $table)
                <div class="col-md-4 mb-4">
                    <div class="form-check form-switch d-flex justify-content-center mb-2">
                        <input 
                            class="form-check-input toggle-lock" 
                            type="checkbox" 
                            data-id="{{ $table->id }}" 
                            {{ $table->is_locked ? 'checked' : '' }}
                            role="switch"
                            id="lockSwitch{{ $table->id }}">
                        <label class="form-check-label ms-2" for="lockSwitch{{ $table->id }}">
                            {{ $table->is_locked ? 'Locked' : 'Unlocked' }}
                        </label>
                    </div>


                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Table #{{ $table->number }}</h5>
                            <p class="text-muted">{{ $table->name ?? 'No label' }}</p>

                            @if ($table->qr_code_path)
                                <img src="{{ asset('storage/' . $table->qr_code_path) }}" alt="QR Code" class="img-fluid mb-3" style="max-height: 200px;">
                            @endif
                            @if (Auth::user()->role === 1)

                            <form action="{{ route('tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Delete this table?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Delete</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p>No tables added yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Add Table Modal -->
    <div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTableModalLabel"><i class="bi bi-plus-circle me-2"></i>Add Table</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="number" class="form-label">Table Number <span class="text-danger">*</span></label>
                            <input type="number" name="number" id="number" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Table Name/Label</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="e.g., VIP, Garden">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Table</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Set CSRF token for Axios (required by Laravel)
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Find all lock/unlock switches
    document.querySelectorAll('.toggle-lock').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const tableId = this.dataset.id;
            const isLocked = this.checked;

            axios.post(`/resto-tables/${tableId}/toggle-lock`, {
                is_locked: isLocked
            })
            .then(response => {
                // Update label text (optional)
                const label = this.nextElementSibling;
                if (label) {
                    label.textContent = isLocked ? 'Locked' : 'Unlocked';
                }
            })
            .catch(error => {
                alert('Failed to update status.');
                console.error(error); // debug in console
                this.checked = !isLocked; // revert switch
            });
        });
    });
});
</script>

</x-app-layout>
