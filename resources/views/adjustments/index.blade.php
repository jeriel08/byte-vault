<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header with Search and Add Adjustment -->
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <div class="input-group w-50">
                <input type="text" class="search-input" placeholder="Search by adjustment ID" aria-label="Search adjustmentID">
                <button class="search-button d-flex align-items-center justify-content-center" type="button">
                    <span class="material-icons-outlined">search</span>
                </button>
            </div>
            <x-primary-button href="{{ route('adjustments.create') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                New Adjustment
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
                        
                        <!-- Adjustment Reason -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Adjustment Reason</label>
                            <div class="btn-group d-flex flex-wrap gap-2 mb-3" role="group">
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('reason') === 'Damaged' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="reason" data-value="Damaged">
                                    <span class="badge me-2">0</span> Damaged
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('reason') === 'Lost' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="reason" data-value="Lost">
                                    <span class="badge me-2">0</span> Lost
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('reason') === 'Other' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="reason" data-value="Other">
                                    <span class="badge me-2">0</span> Other
                                </button>
                            </div>
                        </div>
                        <hr>
                        <!-- Date Range -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Date Range</label>
                            <!-- Add your date picker here -->
                        </div>
                        <hr>
                        <!-- Sort By -->
                        <div class="mb-3">
                            <label for="sortBy" class="form-label fw-semibold mb-2">Sort By</label>
                            <select class="form-select" id="sortBy" name="sort_by">
                                <option value="adjustmentDate">Adjustment Date</option>
                                <option value="totalQuantity">Total Quantity</option>
                                <option value="created_by">Created By</option>
                            </select>
                        </div>
                        <hr>
                        <div>
                            <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">Clear Filters</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adjustment List -->
            <div class="col-lg-9 col-md-8 col-sm-12 product-table" id="adjustmentTable">
                @if ($adjustments->isEmpty())
                    <div class="card account-manager-card text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            No adjustments yet. 
                            <span class="material-icons-outlined fs-2">
                                inventory
                            </span>
                        </h5>
                    </div>
                @else
                    <div class="row">
                        @foreach ($adjustments as $adjustment)
                            <div class="col-12 mb-4">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-semibold fs-5 me-4">Adjustment No. {{ $adjustment->adjustmentID }}</p>
                                    </div>
                    
                                    <div class="d-flex align-items-center flex-grow-1 pe-0">
                                        <span class="vr me-4"></span>
                                        <div class="d-flex flex-row gap-3 align-items-start ps-4">
                                            <div class="text-start me-4" style="width: 8rem;">
                                                <span class="text-muted d-block"><small>Created By</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $adjustment->createdBy->full_name ?? 'Unknown' }}</span>                                            </div>
                                            <div class="text-start me-4" style="width: 10rem;">
                                                <span class="text-muted d-block"><small>Adjustment Date</small></span>
                                                <span class="fw-semibold text-truncate d-block">
                                                    {{ \Carbon\Carbon::parse($adjustment->adjustmentDate)->format('M d, Y') }}
                                                </span>
                                            </div>
                                            <div class="text-start me-4" style="width: 10rem;">
                                                <span class="text-muted d-block"><small>Reason</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $adjustment->adjustmentReason }}</span>
                                            </div>
                                            <div class="text-start" style="width: 8rem;">
                                                <span class="text-muted d-block"><small>Quantity Adjusted</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $adjustment->stockOut->totalQuantity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Dropdown for options -->
                                    <div class="ms-5">
                                        <div class="dropdown supplier-order-dropdown">
                                            <a class="btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="material-icons-outlined fs-2">more_horiz</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('adjustments.show', $adjustment->adjustmentID) }}">
                                                        <span class="material-icons-outlined align-middle me-2">visibility</span> View
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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