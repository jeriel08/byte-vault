<nav class="navbar pos-navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Left side (70%) -->
        <div class="d-flex align-items-center" style="width: 70%;">
            <a class="navbar-brand" href="{{ route('pos.products') }}">
                <img src="{{ asset('images/logo-cropped.png') }}" alt="POS System">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearchContent"
                aria-controls="navbarSearchContent" aria-expanded="false" aria-label="Toggle search">
                <span class="material-icons-outlined">search</span>
            </button>
            <!-- Navigation Buttons and Search -->
            <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center nav-btn-group">
                <!-- Search Bar for Desktop and Tablet -->
                <li class="nav-item d-none d-md-block">
                    <div class="search-bar-container">
                        <span class="material-icons-outlined me-2">search</span>
                        <input type="text" class="search-input" placeholder="Search">
                    </div>
                </li>
                <!-- Search Button for Mobile -->
                <li class="nav-item d-md-none">
                    <a class="nav-link nav-btn search-btn" href="#" data-bs-toggle="collapse"
                        data-bs-target="#navbarSearchContent" aria-controls="navbarSearchContent" aria-expanded="false"
                        aria-label="Toggle search" title="Search">
                        <span class="material-icons-outlined">search</span>
                    </a>
                </li>
                <!-- POS Button -->
                <li class="nav-item">
                    <a class="nav-link nav-btn {{ request()->routeIs('pos.products') ? 'active' : '' }}"
                        href="{{ route('pos.products') }}" title="POS">
                        <span class="material-icons-outlined">point_of_sale</span>
                        <span class="nav-text d-none d-lg-inline">Point of Sale</span>
                    </a>
                </li>
                <!-- Sales Button -->
                <li class="nav-item">
                    <a class="nav-link nav-btn {{ request()->routeIs('pos.sales') ? 'active' : '' }}"
                        href="{{ route('pos.sales') }}" title="Sales">
                        <span class="material-icons-outlined">receipt_long</span>
                        <span class="nav-text d-none d-lg-inline">Sales</span>
                    </a>
                </li>
                <!-- Switch to Inventory for Admin and Manager -->
                @auth
                    @if (in_array(auth()->user()->role, ['Admin', 'Manager']))
                        <li class="nav-item">
                            <a class="nav-link nav-btn" href="{{ route('dashboard') }}" title="Switch to Inventory">
                                <span class="material-icons-outlined">inventory</span>
                                <span class="nav-text d-none d-lg-inline">Inventory</span>
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
            <div class="collapse navbar-collapse" id="navbarSearchContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <!-- Search Bar Dropdown for Mobile -->
                    <li class="nav-item d-md-none">
                        <div class="search-bar-container">
                            <span class="material-icons-outlined me-2">search</span>
                            <input type="text" class="search-input" placeholder="Search">
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right side (30%) -->
        <div class="d-flex justify-content-end" style="width: 30%;">
            <!-- Right-side account section -->
            <div class="d-flex align-items-center account-section">
                <div class="d-flex align-items-center me-2">
                    <span class="material-icons-outlined me-1 profile-icon">account_circle</span>
                    <div>
                        <p class="fw-bold mb-0">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</p>
                        <small class="mt-0">{{ Auth::user()->role }}</small>
                    </div>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn border-0 bg-transparent p-0">
                            <span class="material-icons-outlined">arrow_drop_down</span>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                            <span class="material-icons-outlined">settings</span>
                            Account Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="material-icons-outlined">logout</span>
                                Logout
                            </a>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>