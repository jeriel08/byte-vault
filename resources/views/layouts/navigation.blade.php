<nav class="navbar fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Left side: Button and Header -->
        <div class="d-flex align-items-center">
            <button
                class="navbar-toggler mx-3 border-0 shadow-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar"
                aria-label="Toggle navigation">
                <span class="material-icons-outlined navbar-icon"> menu </span>
            </button>

            <a class="navbar-brand fw-semibold">
                @php
                    $routeTitles = [
                        'dashboard' => 'Dashboard',
                        'profile.edit' => 'Account Settings',
                        'account.manager' => 'Account Manager',
                        'account.add' => 'Add Account',
                        'account.edit' => 'Edit Account',
                        'suppliers.index' => 'Suppliers',
                        'suppliers.create' => 'Add Supplier',
                        'suppliers.edit' => 'Edit Supplier',
                        'brands.index' => 'Brands',
                        'brands.create' => 'Add Brand',
                        'brands.edit' => 'Edit Brand',
                        'categories.index' => 'Categories',
                        'categories.create' => 'Add Category',
                        'categories.edit' => 'Edit Category',
                        'products.index' => 'Products',
                        'products.create' => 'Add Product',
                        'products.edit' => 'Edit Product',
                        'supplier_orders.index' => 'Supplier Orders',
                        'supplier_orders.create' => 'Add Supplier Order',
                        'supplier_orders.edit' => 'Edit Supplier Order',
                        // Add more routes as needed
                    ];
                    $currentRoute = Route::currentRouteName();
                    echo strtoupper($routeTitles[$currentRoute] ?? str_replace('.', ' ', $currentRoute));
                @endphp
            </a>
        </div>

        <!-- Right side: Account Section with Dropdown -->
        <div class="d-flex align-items-center me-4 ms-auto">
            <!-- User Info -->
            <div class="d-flex align-items-center me-2">
                <span class="material-icons-outlined me-2 fs-1 text-dark">account_circle</span>
                <div>
                    <p class="fw-bold mb-0 text-dark">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</p>
                    <small class="mt-0 text-muted">{{ Auth::user()->role }}</small>
                </div>
            </div>

            <!-- Dropdown Component -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="btn border-0 bg-transparent p-0 ms-2">
                        <span class="material-icons-outlined">arrow_drop_down</span>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        Account Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Offcanvas Menu -->
        <div
            class="offcanvas offcanvas-start"
            tabindex="-1"
            id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header d-flex align-items-center mt-4">
                <div class="col-10 mx-auto">
                    <img
                        src="{{ asset('images/logo-cropped.png') }}"
                        alt="SmartStock Inventory Logo"
                        class="img-fluid" />
                </div>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 px-auto">
                    <li class="nav-item">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> dashboard </span>
                            Dashboard
                        </x-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> inventory_2 </span>
                            Products
                        </x-nav-link>
                    </li>

                    {{-- <li class="nav-item">
                        <x-nav-link :href="route('orders')" :active="request()->routeIs('orders')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> shopping_cart </span>
                            Customer Orders
                        </x-nav-link>
                    </li> --}}
                   
                    <li class="nav-item">
                        <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.index')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> inventory </span>
                            Suppliers
                        </x-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-nav-link :href="route('supplier_orders.index')" :active="request()->routeIs('supplier_orders.index')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> local_shipping </span>
                            Supplier Orders
                        </x-nav-link>
                    </li>

                    {{-- <li class="nav-item">
                        <x-nav-link :href="route('returns')" :active="request()->routeIs('returns')" class="btn btn-outline-dark d-flex align-items-center gap-2 my-3 py-2 px-4">
                            <span class="material-icons-outlined"> assignment_return </span>
                            Returns
                        </x-nav-link>
                    </li> --}}

                    @auth
                        @if (auth()->user()->role === 'Admin')
                            <hr>
                            <li class="nav-item">
                                <h6 class="text-muted px-4 ">Admin Controls</h6>
                            </li>
                            <li class="nav-item">
                                <x-nav-link :href="route('account.manager')" :active="request()->routeIs('account.manager')" class="btn btn-outline-dark d-flex align-items-center gap-2 mb-3 py-2 px-4">
                                    <span class="material-icons-outlined"> manage_accounts </span>
                                    {{ __('Account Manager') }}
                                </x-nav-link>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>