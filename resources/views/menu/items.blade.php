<x-app-layout>
    <div class="container py-5">
@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif


        <!-- Back & Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('menu.categories') }}" class="btn btn-secondary">← Back to Categories</a>
            @if (Auth::user()->role === 1)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuItemModal">
                <i class="bi bi-plus-circle"></i> Add Menu Item
            </button>
            @endif
        </div>

        <!-- Category Title -->
        <h2 class="mb-4">{{ $category->name }} Items</h2>

        <!-- Menu Item Cards -->
        <div class="row">
            @forelse ($category->menuItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 hover-shadow">
                        @if($item->image)
                        <img 
                                src="{{ asset('storage/' . $item->image) }}" 
                                class="card-img-top img-fluid" 
                                style="max-height: 150px; object-fit: contain;" 
                                alt="{{ $item->name }}"
                            >
                        @else
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Placeholder">
                        @endif
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text">{{ $item->description }}</p>
                                <p class="card-text fw-bold">₱{{ number_format($item->price, 2) }}</p>
                                <span class="badge bg-{{ $item->available ? 'success' : 'secondary' }}">
                                    {{ $item->available ? 'Available' : 'Not Available' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <!-- Edit -->
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMenuItemModal{{ $item->id }}">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                @if (Auth::user()->role === 1)
                                <!-- Delete -->
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMenuItemModal{{ $item->id }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No items found under this category.</p>
            @endforelse
        </div>

        <!-- Add Menu Modal -->
        <div class="modal fade" id="addMenuItemModal" tabindex="-1" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-plus-circle text-primary me-2"></i>Add Menu Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
                            <textarea name="description" class="form-control mb-3" rows="3" placeholder="Description"></textarea>
                            <input type="number" step="0.01" name="price" class="form-control mb-3" placeholder="Price" required>
                            <input type="file" name="image" class="form-control mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available" id="available">
                                <label class="form-check-label" for="available">Available</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit & Delete Modals per Item -->
        @foreach ($category->menuItems as $item)
            <!-- Edit Modal -->
            <div class="modal fade" id="editMenuItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editMenuItemModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('menu.update', $item->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-pencil-fill text-primary me-2"></i>Edit Menu Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-3" value="{{ $item->name }}" required>
                                <textarea name="description" class="form-control mb-3" rows="3">{{ $item->description }}</textarea>
                                <input type="number" step="0.01" name="price" class="form-control mb-3" value="{{ $item->price }}" required>
                                <input type="file" name="image" class="form-control mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="available" value="0">
                                    <input class="form-check-input" type="checkbox" name="available" id="available{{ $item->id }}" value="1" {{ $item->available ? 'checked' : '' }}>
                                    <label class="form-check-label" for="available{{ $item->id }}">Available</label>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteMenuItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteMenuItemModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('menu.destroy', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-trash-fill text-danger me-2"></i>Delete Menu Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <strong>{{ $item->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Optional styling -->
    <style>
        .hover-shadow {
            transition: all 0.2s ease-in-out;
        }
        .hover-shadow:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-4px);
        }
        .btn-outline-primary:hover {
            background-color: #0d6efd !important;
            color: #fff !important;
            border-color: #0d6efd !important;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }
    </style>
</x-app-layout>
