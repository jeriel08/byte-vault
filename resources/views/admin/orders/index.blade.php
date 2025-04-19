<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header with Search and Add Supplier Order-->
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <div class="input-group w-50">
                <input type="text" class="search-input" placeholder="Search by order ID" aria-label="Search orderID">
                <button class="search-button d-flex align-items-center justify-content-center" type="button">
                    <span class="material-icons-outlined">search</span>
                </button>
            </div>
            <x-primary-button href="{{ route('pos.products') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                Add Customer Order
            </x-primary-button>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Filter Panel -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card filter-panel">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold">Filters</h5>

                        <!-- Date Range -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Date Range</label>
                            <small class="text-muted ms-1">From</small>
                            <input type="date" class="form-control mb-2" id="dateFrom" name="date_from" value="{{ request('date_from') }}" placeholder="From">
                            <small class="text-muted ms-1">To</small>
                            <input type="date" class="form-control" id="dateTo" name="date_to" value="{{ request('date_to') }}" placeholder="To">
                        </div>
                        <hr>
                        <!-- Sort By -->
                        <div class="mb-3">
                            <label for="sortBy" class="form-label fw-semibold mb-2">Sort By</label>
                            <select class="form-select" id="sortBy" name="sort_by">
                                <option value="date_desc" {{ request('sort_by') === 'date_desc' ? 'selected' : '' }}>Order Date: Recent First</option>
                                <option value="date_asc" {{ request('sort_by') === 'date_asc' ? 'selected' : '' }}>Order Date: Oldest First</option>
                                <option value="amount_desc" {{ request('sort_by') === 'amount_desc' ? 'selected' : '' }}>Amount: High to Low</option>
                                <option value="amount_asc" {{ request('sort_by') === 'amount_asc' ? 'selected' : '' }}>Amount: Low to High</option>
                            </select>
                        </div>
                        <hr>
                        <div>
                            <button type="button" class="btn btn-outline-danger w-100" id="clearFilters">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Order Cards -->
            <div class="col-lg-9 col-md-8 col-sm-12 product-table" id="orderTable">
                @if ($orders->isEmpty())
                    <div class="text-center text-muted py-3">
                        There's no order yet.
                    </div>
                @else
                    <div class="row">
                        @foreach ($orders as $order)
                            <div class="col-12 mb-4">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-semibold fs-5 me-4">Order No. {{ $order->orderID }}</p>
                                    </div>
                    
                                    <div class="d-flex align-items-center mx-3 price-section">
                                        <span class="vr me-4"></span>
                                        <div class="d-flex flex-row gap-3 align-items-start">
                                            <div class="text-start me-4" style="width: 7rem;">
                                                <span class="text-muted d-block"><small>Customer</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $order->customer ? $order->customer->name : 'N/A' }}</span>
                                            </div>
                                            <div class="text-start me-4" style="width: 7rem;">
                                                <span class="text-muted d-block"><small>Total Amount</small></span>
                                                <span class="fw-semibold text-truncate d-block">₱{{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ms-5 d-flex flex-column gap-2">
                                        <x-primary-button class="btn-sm" href="{{ route('orders.show', $order) }}">
                                            <span class="material-icons-outlined">visibility</span>
                                        </x-primary-button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for Filter Interactivity -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.category-filter-button');
            const productFilter = document.getElementById('productFilter');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const sortBy = document.getElementById('sortBy');
            const clearFiltersBtn = document.getElementById('clearFilters');

            function applyFilters() {
                const params = new URLSearchParams(window.location.search);
                
                // Status filter
                const activeButton = document.querySelector('.category-filter-button.btn-primary');
                if (activeButton && activeButton.dataset.value) {
                    params.set('status', activeButton.dataset.value);
                } else {
                    params.delete('status');
                }

                // Product filter
                if (productFilter.value) {
                    params.set('product_id', productFilter.value);
                } else {
                    params.delete('product_id');
                }

                // Date range filter
                if (dateFrom.value) {
                    params.set('date_from', dateFrom.value);
                } else {
                    params.delete('date_from');
                }
                if (dateTo.value) {
                    params.set('date_to', dateTo.value);
                } else {
                    params.delete('date_to');
                }

                // Sort by
                params.set('sort_by', sortBy.value);

                window.location.href = `${window.location.pathname}?${params.toString()}`;
            }

            // Status button toggle
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                    applyFilters();
                });
            });

            // Other filters (product, date, sort)
            [productFilter, dateFrom, dateTo, sortBy].forEach(element => {
                element.addEventListener('change', applyFilters);
            });

            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                filterButtons.forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                productFilter.value = '';
                dateFrom.value = '';
                dateTo.value = '';
                sortBy.value = 'date_desc';
                window.location.href = window.location.pathname;
            });
        });
    </script>
</x-app-layout>