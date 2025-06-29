<x-app-layout>
  <div class="container py-5">
      <h1 class="mb-4">Restaurant Menu</h1>

      @foreach ($categories as $category)
          <h2 class="mt-5">{{ $category->name }}</h2>
          <div class="row">
              @forelse ($category->menuItems as $item)
                  <div class="col-md-4 mb-4">
                      <div class="card h-100">
                          @if($item->image)
                              <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                          @else
                              <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Placeholder">
                          @endif
                          <div class="card-body">
                              <h5 class="card-title">{{ $item->name }}</h5>
                              <p class="card-text">{{ $item->description }}</p>
                              <p class="card-text fw-bold">₱{{ number_format($item->price, 2) }}</p>
                              <span class="badge bg-{{ $item->available ? 'success' : 'secondary' }}">
                                  {{ $item->available ? 'Available' : 'Not Available' }}
                              </span>
                          </div>
                      </div>
                  </div>
              @empty
                  <p>No menu items under {{ $category->name }}.</p>
              @endforelse
          </div>
      @endforeach
  </div>
</x-app-layout>
