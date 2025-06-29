<x-app-layout>
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
  @if (session('success'))
  <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

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

    <div class="pagetitle">
        <h1>Menu Categories</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Menu</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="d-flex justify-content-between align-items-center mb-4">
            @if (Auth::user()->role === 1)
             <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg"></i> Add Category
            </button>
            @endif
           
           
              <!-- Add Category Modal -->
                <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Category Name</label>
                                    <input type="text" name="name" id="categoryName" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
        </div>

        <div class="row">
            @foreach ($categories as $category)
            <div class="col-xl-3 col-md-6 mb-4">
                
    <div class="card info-card hover-shadow border shadow-sm h-100 d-flex flex-column justify-content-between">
        <div class="card-body text-center py-4">

            <!-- Clickable Area (category icon + name) -->
            <a href="{{ route('menu.byCategory', $category->id) }}" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-center mt-2 mb-3">
                    <div class="icon rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-folder-fill fs-4"></i>
                    </div>
                </div>
                <h5 class="card-title text-dark mb-0">{{ $category->name }}</h5>
            </a>

            <!-- Edit/Delete Buttons -->
            <div class="d-flex justify-content-center gap-2 mt-3">
                @if (Auth::user()->role === 1)
                <!-- Edit -->
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}" title="Edit">
                    <i class="bi bi-pencil-fill"></i>
                </button>

                <!-- Delete -->
                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->id }}" title="Delete">
                    <i class="bi bi-trash-fill"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

              

                                <!-- Edit Modal -->
                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
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
                <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>?</p>
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
    </section>
</x-app-layout>
