<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Accepted Orders (User Role Only) -->
    @if (Auth::user()->role === 2)
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('orders.accepted') ? 'active' : '' }}" href="{{ route('orders.accepted') }}">
          <i class="bi bi-check2-square"></i>
          <span>Accepted Orders</span>
        </a>
      </li>
    @endif

    <!-- Manage Menu -->
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('menu.categories') ? 'active' : '' }}" href="{{ route('menu.categories') }}">
        <i class="bi bi-list-ul"></i>
        <span>Manage Menu</span>
      </a>
    </li>

    <!-- Manage Table -->
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('tables.index') ? 'active' : '' }}" href="{{ route('tables.index') }}">
        <i class="bi bi-card-list"></i>
        <span>Manage Table</span>
      </a>
    </li>

    <!-- Admin-Only Links -->
    @if (Auth::user()->role === 1)

      <!-- Manage Orders -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('orders.manage') ? 'active' : '' }}" href="{{ route('orders.manage') }}">
          <i class="bi bi-receipt"></i>
          <span>Manage Orders</span>
        </a>
      </li>

      <!-- Manage User -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
          <i class="bi bi-person-lines-fill"></i>
          <span>Manage User</span>
        </a>
      </li>

      <!-- Sales Report -->
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('order.sales') ? 'active' : '' }}" href="{{ route('order.sales') }}">
          <i class="bi bi-bar-chart-line"></i>
          <span>Sales Report</span>
        </a>
      </li>

    @endif

  </ul>

</aside><!-- End Sidebar -->
