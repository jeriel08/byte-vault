<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-2xl font-bold mb-4">Return No. {{ $return->returnSupplierID }}</h1>
            <x-secondary-button href="{{ route('supplier_returns.index') }}">
                <span class="material-icons-outlined">arrow_back</span>
                Go back
            </x-secondary-button>
        </div>
        
        <div class="card account-settings-card p-3">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Return Information</h5>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Created By:</label>
                    <p class="form-control-plaintext">
                        {{ $return->creator->full_name ?? 'Unknown' }}
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supplier Order:</label>
                    <p class="form-control-plaintext">
                        Order #{{ $return->supplierOrder->supplierOrderID }} - {{ $return->supplierOrder->supplier->name }}
                        ({{ \Carbon\Carbon::parse($return->supplierOrder->orderDate)->format('M d, Y') }})
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Return Date:</label>
                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($return->returnDate)->format('M d, Y') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Return Reason:</label>
                    <p class="form-control-plaintext">{{ $return->returnSupplierReason }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status:</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $return->status === 'Completed' ? 'bg-success' : ($return->status === 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ $return->status }}
                        </span>
                    </p>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Total Quantity Returned:</label>
                    <p class="form-control-plaintext">{{ $return->stockOut->totalQuantity }}</p>
                </div>
                <hr class="mb-4">
                
                <!-- Return Details Section -->
                <div id="returnDetails" class="mb-3">
                    <h5 class="fw-semibold mb-3">Return Details</h5>
                    <div id="productList">
                        @foreach ($return->stockOut->details as $detail)
                            <div class="card account-manager-card p-3 d-flex flex-row align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fw-semibold">{{ $detail->product->productName }}</h5>
                                </div>
                                <div class="d-flex align-items-center mx-3">
                                    <span class="vr me-5"></span>
                                    <div class="d-flex flex-row gap-3 align-items-start">
                                        <div class="text-start me-4" style="min-width: 80px;">
                                            <span class="text-muted d-block"><small>Quantity</small></span>
                                            <span class="fw-semibold fs-5">{{ $detail->quantity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>