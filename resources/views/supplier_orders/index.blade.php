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
            <x-primary-button href="{{ route('supplier_orders.create') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                Add Order
            </x-primary-button>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row border">
            <!-- Filter Panel -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card filter-panel">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">Filters</h5>
                        
                        <!-- Add filter options here (e.g., status, supplier, date range) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Order Status</label>
                            <div class="btn-group d-flex flex-wrap gap-2 mb-3" role="group">
                                <button type="button" class="btn category-filter-button flex-grow-1">
                                    <span class="badge me-2">{{ $supplierOrders->where('status', 'Pending')->count() }}</span> Pending
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1">
                                    <span class="badge me-2">{{ $supplierOrders->where('status', 'Cancelled')->count() }}</span> Cancelled
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1">
                                    <span class="badge me-2">{{ $supplierOrders->where('status', 'Received')->count() }}</span> Received
                                </button>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="sortBy" class="form-label fw-semibold mb-2">Sort By</label>
                            <select class="form-select" id="sortBy">
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="date_asc">Date: Recent</option>
                                <option value="date_desc">Date: Old</option>
                            </select>
                        </div>
                        <!-- Add more filters as needed -->
                    </div>
                </div>
            </div>

            <!-- Main Content: Supplier Order Cards -->
            <div class="col-lg-9 col-md-8 col-sm-12 product-table" id="supplierOrderTable">
                @if ($supplierOrders->isEmpty())
                    <div class="card account-manager-card text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            No supplier orders yet. 
                            <span class="material-icons-outlined fs-2">
                                local_shipping
                            </span>
                        </h5>
                    </div>
                @else
                    <div class="row">
                        @foreach ($supplierOrders as $supplierOrder)
                            <div class="col-12 mb-4">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-semibold fs-5 me-4">Order No. {{ $supplierOrder->supplierOrderID }}</p>
                                    </div>
                    
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <span class="vr me-4"></span>
                                        <div class="d-flex flex-row gap-3 align-items-start">
                                            <div class="text-start me-4" style="width: 16rem;">
                                                <span class="text-muted d-block"><small>Supplier</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $supplierOrder->supplier->supplierName }}</span>
                                            </div>
                                            <div class="text-start me-4" style="width: 7rem;">
                                                <span class="text-muted d-block"><small>Ordered</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $supplierOrder->orderDate->format('M d, Y') }}</span>
                                            </div>
                                            <div class="text-start me-4" style="width: 5rem;">
                                                <span class="text-muted d-block"><small>Status</small></span>
                                                <span class="badge bg-{{ $supplierOrder->status === 'Pending' ? 'warning' : ($supplierOrder->status === 'Received' ? 'success' : 'danger') }}">
                                                    {{ $supplierOrder->status }}
                                                </span>
                                            </div>
                                            <div class="text-start me-4" style="width: 8rem;">
                                                <span class="text-muted d-block"><small>Total Cost</small></span>
                                                <span class="fw-semibold text-truncate d-block">₱{{ number_format($supplierOrder->totalCost, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ms-5 d-flex flex-column gap-2">
                                        <x-primary-button class="btn-sm" href="{{ route('supplier_orders.show', $supplierOrder->supplierOrderID) }}">
                                            <span class="material-icons-outlined">visibility</span>
                                        </x-primary-button>
                                        @if ($supplierOrder->status != 'Received')
                                            <x-primary-button href="{{ route('supplier_orders.edit', $supplierOrder->supplierOrderID) }}" class="btn-sm">
                                                <span class="material-icons-outlined">edit</span>
                                            </x-primary-button>
                                        @else
                                            <x-primary-button href="{{ route('supplier_orders.create', ['reorder' => $supplierOrder->supplierOrderID]) }}" class="btn-sm">
                                                <span class="material-icons-outlined">replay</span>
                                            </x-primary-button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>