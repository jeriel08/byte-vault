<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="row">

            <!-- Filter Panel -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card p-3 sticky-top" style="top: 20px;">
                    <h5 class="fw-semibold mb-3">Filters</h5>
                    <!-- Add filter options here (e.g., status, supplier, date range) -->
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All</option>
                            <option value="Pending">Pending</option>
                            <option value="Received">Received</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <!-- Add more filters as needed -->
                </div>
            </div>

            <!-- Main Content: Supplier Order Cards -->
            <div class="col-lg-9 col-md-8 col-sm-12 product-table" id="supplierOrderTable">
                <h1 class="text-2xl font-bold mb-4">Supplier Orders</h1>
                @if ($supplierOrders->isEmpty())
                    <div class="text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            No supplier orders yet.
                            <span class="material-icons-outlined fs-2">local_shipping</span>
                        </h5>
                    </div>
                @else
                    <div class="row">
                        @foreach ($supplierOrders as $supplierOrder)
                            <div class="col-12 mb-4">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold fs-4">Order No. {{ $supplierOrder->supplierOrderID }}</h5>
                                        <p class="mb-0 text-muted">
                                            {{ $supplierOrder->supplier->supplierName }} • 
                                            Ordered: {{ $supplierOrder->orderDate->format('M d, Y') }} • 
                                            <span class="badge bg-{{ $supplierOrder->status === 'Pending' ? 'warning' : ($supplierOrder->status === 'Received' ? 'success' : 'danger') }}">
                                                {{ $supplierOrder->status }}
                                            </span>
                                        </p>
                                    </div>
                    
                                    <div class="d-flex align-items-center mx-3 price-section">
                                        <span class="vr me-3"></span>
                                        <div class="d-flex flex-column">
                                            <span class="text-muted"><small>Total Cost</small></span>
                                            <span class="fw-semibold fs-5">₱{{ number_format($supplierOrder->totalCost, 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="ms-5 d-flex flex-column gap-2">
                                        <x-primary-button class="btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetailsModal-{{ $supplierOrder->supplierOrderID }}">
                                            <span class="material-icons-outlined">visibility</span>
                                        </x-primary-button>
                                        <x-primary-button href="{{ route('supplier_orders.edit', $supplierOrder->supplierOrderID) }}" class="btn-sm">
                                            <span class="material-icons-outlined">edit</span>
                                        </x-primary-button>
                                    </div>

                                    <!-- Modal for Order Details -->
                                    <x-modal name="orderDetailsModal-{{ $supplierOrder->supplierOrderID }}" maxWidth="lg">
                                        <div class="modal-header custom-modal-header">
                                            <h5 class="modal-title" id="orderDetailsModal-{{ $supplierOrder->supplierOrderID }}-label">Order No. {{ $supplierOrder->supplierOrderID }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body custom-modal-body">
                                            <p><strong class="label">Supplier:</strong> <span class="value">{{ $supplierOrder->supplier->supplierName }}</span></p>
                                            <p><strong class="label">Order Date:</strong> <span class="value">{{ $supplierOrder->orderDate->format('M d, Y') }}</span></p>
                                            <p><strong class="label">Expected Delivery:</strong> <span class="value">{{ $supplierOrder->expectedDeliveryDate?->format('M d, Y') ?? 'Not set' }}</span></p>
                                            <p><strong class="label">Total Cost:</strong> <span class="value">₱{{ number_format($supplierOrder->totalCost, 2) }}</span></p>
                                            <p><strong class="label">Status:</strong> <span class="value">{{ $supplierOrder->status }}</span></p>
                                            <div>
                                                <strong class="label">Order Details:</strong>
                                                <ul class="list-group mt-2">
                                                    @foreach ($supplierOrder->details as $detail)
                                                        <li class="list-group-item">
                                                            {{ $detail->product->productName }} - 
                                                            Quantity: {{ $detail->quantity }} - 
                                                            Unit Cost: ₱{{ number_format($detail->unitCost, 2) }} - 
                                                            Received: {{ $detail->receivedQuantity }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="modal-footer custom-modal-footer">
                                            <button type="button" class="btn custom-btn-close" data-bs-dismiss="modal">Close</button>
                                        </div>
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