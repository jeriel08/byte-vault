<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header with Search and Add Return -->
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <div class="input-group w-50">
                <input type="text" class="search-input" placeholder="Search by return ID" aria-label="Search returnSupplierID">
                <button class="search-button d-flex align-items-center justify-content-center" type="button">
                    <span class="material-icons-outlined">search</span>
                </button>
            </div>
            <x-primary-button href="{{ route('supplier_returns.create') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                New Return
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
                        
                        <!-- Status Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Status</label>
                            <div class="btn-group d-flex flex-wrap gap-2 mb-3" role="group">
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('status') === 'Pending' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="status" data-value="Pending">
                                    <span class="badge me-2">0</span> Pending
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('status') === 'Completed' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="status" data-value="Completed">
                                    <span class="badge me-2">0</span> Completed
                                </button>
                                <button type="button" class="btn category-filter-button flex-grow-1 {{ request('status') === 'Rejected' ? 'btn-primary' : 'btn-outline-primary' }}" data-filter="status" data-value="Rejected">
                                    <span class="badge me-2">0</span> Rejected
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
                                <option value="returnDate">Return Date</option>
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

            <!-- Returns List -->
            <div class="col-lg-9 col-md-8 col-sm-12 product-table" id="returnsTable">
                @if ($returns->isEmpty())
                    <div class="card account-manager-card text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            No returns yet.
                            <span class="material-icons-outlined fs-2">
                                inventory
                            </span>
                        </h5>
                    </div>
                @else
                    <div class="row">
                        @foreach ($returns as $return)
                            <div class="col-12 mb-4">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-semibold fs-5 me-4">Return No. {{ $return->returnSupplierID }}</p>
                                    </div>
                    
                                    <div class="d-flex align-items-center flex-grow-1 pe-0">
                                        <span class="vr me-4"></span>
                                        <div class="d-flex flex-row gap-3 align-items-start ps-4">
                                            <div class="text-start me-4" style="width: 8rem;">
                                                <span class="text-muted d-block"><small>Created By</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $return->creator->full_name ?? 'Unknown' }}</span>
                                            </div>
                                            <div class="text-start me-4" style="width: 10rem;">
                                                <span class="text-muted d-block"><small>Return Date</small></span>
                                                <span class="fw-semibold text-truncate d-block">
                                                    {{ \Carbon\Carbon::parse($return->returnDate)->format('M d, Y') }}
                                                </span>
                                            </div>
                                            <div class="text-start me-4" style="width: 10rem;">
                                                <span class="text-muted d-block"><small>Reason</small></span>
                                                <span class="fw-semibold text-truncate d-block">{{ $return->returnSupplierReason }}</span>
                                            </div>
                                            <div class="text-start" style="width: 8rem;">
                                                <span class="text-muted d-block"><small>Status</small></span>
                                                <span class="badge {{ $return->completionDate ? 'bg-success' : ($return->cancellationDate ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ $return->completionDate ? 'Completed' : ($return->cancellationDate ? 'Rejected' : 'Pending') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Dropdown and Status Actions -->
                                    <div class="ms-5">
                                        <div class="dropdown supplier-order-dropdown">
                                            <a class="btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="material-icons-outlined fs-2">more_horiz</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('supplier_returns.show', $return->returnSupplierID) }}">
                                                        <span class="material-icons-outlined align-middle me-2">visibility</span> View
                                                    </a>
                                                </li>
                                                @if (!$return->completionDate && !$return->cancellationDate)
                                                    <li>
                                                        <form action="{{ route('supplier_returns.complete', $return->returnSupplierID) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item">
                                                                <span class="material-icons-outlined align-middle me-2">check_circle</span> Complete
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $return->returnSupplierID }}">
                                                            <span class="material-icons-outlined align-middle me-2">cancel</span> Reject
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <!-- Reject Modal -->
                                    <x-modal name="rejectModal-{{ $return->returnSupplierID }}" maxWidth="md">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModal-{{ $return->returnSupplierID }}-label">Reject Return #{{ $return->returnSupplierID }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('supplier_returns.reject', $return->returnSupplierID) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="rejectionReason-{{ $return->returnSupplierID }}" class="form-label">Reason for Rejection</label>
                                                    <textarea class="form-control" id="rejectionReason-{{ $return->returnSupplierID }}" name="rejectionReason" rows="3" required placeholder="Enter the reason for rejecting this return"></textarea>
                                                    @error('rejectionReason')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <x-danger-button type="submit">Reject Return</x-danger-button>
                                                <x-secondary-button type="button" data-bs-dismiss="modal">Close</x-secondary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>