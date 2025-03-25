<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-2xl font-bold mb-4"> Order #{{ $supplierOrder->supplierOrderID }}</h1>
            <x-secondary-button href="{{ route('supplier_orders.index') }}">
                <span class="material-icons-outlined">arrow_back</span>
                Go back
            </x-secondary-button>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="card account-settings-card p-3">
            <div class="card-body">
                <form action="{{ route('supplier_orders.update', $supplierOrder->supplierOrderID) }}" method="POST" id="supplierOrderForm">
                    @csrf
                    @method('PUT')
                    <h5 class="fw-semibold mb-3">Order Information</h5>
                    <div class="mb-3">
                        <label for="supplierID" class="form-label fw-semibold">Supplier</label>
                        <select name="supplierID" id="supplierID" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplierID }}" {{ $supplierOrder->supplierID == $supplier->supplierID ? 'selected' : '' }}>
                                    {{ $supplier->supplierName }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplierID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="orderDate" class="form-label fw-semibold">Order Date</label>
                        <input type="date" name="orderDate" id="orderDate" class="form-control" value="{{ $supplierOrder->orderDate->format('Y-m-d') }}" required>
                        @error('orderDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expectedDeliveryDate" class="form-label fw-semibold">Expected Delivery Date</label>
                        <input type="date" name="expectedDeliveryDate" id="expectedDeliveryDate" class="form-control" value="{{ $supplierOrder->expectedDeliveryDate?->format('Y-m-d') }}">
                        @error('expectedDeliveryDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Order Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pending" {{ $supplierOrder->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Received" {{ $supplierOrder->status === 'Received' ? 'selected' : '' }}>Received</option>
                            <option value="Cancelled" {{ $supplierOrder->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <hr class="mb-4">
                    <!-- Order Details Section -->
                    <div id="orderDetails" class="mb-3">
                        <h5 class="fw-semibold mb-3">Order Details</h5>
                        <div id="productList">
                            @foreach ($supplierOrder->details as $index => $detail)
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center mb-3" data-detail-id="{{ $detail->supplierOrderDetailID }}">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">{{ $detail->product->productName }}</h5>
                                    </div>
                                    <div class="d-flex align-items-center mx-3">
                                        <span class="vr me-3"></span>
                                        <div class="d-flex flex-row gap-3 align-items-start">
                                            <div class="text-start" style="min-width: 80px;">
                                                <span class="text-muted d-block"><small>Quantity</small></span>
                                                <span class="fw-semibold fs-5">{{ $detail->quantity }}</span>
                                            </div>
                                            <div class="text-start" style="min-width: 100px;">
                                                <span class="text-muted d-block"><small>Unit Cost</small></span>
                                                <span class="fw-semibold fs-5">₱{{ number_format($detail->unitCost, 2) }}</span>
                                            </div>
                                            <div class="text-start" style="min-width: 100px;">
                                                <span class="text-muted d-block"><small>Received Qty</small></span>
                                                <span class="fw-semibold fs-5">{{ $detail->receivedQuantity }}</span>
                                            </div>
                                            <div class="text-start" style="min-width: 100px;">
                                                <span class="text-muted d-block"><small>Status</small></span>
                                                <span class="badge bg-{{ $detail->status === 'Pending' ? 'warning' : ($detail->status === 'Received' ? 'success' : 'danger') }}">
                                                    {{ $detail->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-5 d-flex gap-2">
                                        <x-primary-button type="button" class="btn-sm edit-product" data-bs-toggle="modal" data-bs-target="#editProductModal-{{ $detail->supplierOrderDetailID }}">
                                            <span class="material-icons-outlined">edit</span>
                                        </x-primary-button>
                                    </div>
                                    <input type="hidden" name="details[{{ $index }}][supplierOrderDetailID]" value="{{ $detail->supplierOrderDetailID }}">
                                    <input type="hidden" name="details[{{ $index }}][productID]" value="{{ $detail->productID }}">
                                    <input type="hidden" name="details[{{ $index }}][quantity]" value="{{ $detail->quantity }}">
                                    <input type="hidden" name="details[{{ $index }}][unitCost]" value="{{ $detail->unitCost }}">
                                    <input type="hidden" name="details[{{ $index }}][receivedQuantity]" value="{{ $detail->receivedQuantity }}">
                                    <input type="hidden" name="details[{{ $index }}][status]" value="{{ $detail->status }}">
                                </div>
                        
                                <!-- Edit Product Modal -->
                                <x-modal name="editProductModal-{{ $detail->supplierOrderDetailID }}" maxWidth="lg">
                                    <div class="modal-header custom-modal-header">
                                        <h5 class="modal-title">Edit Product: {{ $detail->product->productName }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body custom-modal-body">
                                        <div class="row mb-3">
                                            <label class="form-label fw-semibold">Quantity</label>
                                            <input type="number" class="form-control edit-quantity" value="{{ $detail->quantity }}" min="1" required>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label fw-semibold">Unit Cost</label>
                                            <input type="number" class="form-control edit-unitCost" value="{{ $detail->unitCost }}" step="0.01" min="0" required>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label fw-semibold">Received Quantity</label>
                                            <input type="number" class="form-control edit-receivedQuantity" value="{{ $detail->receivedQuantity }}" min="0" max="{{ $detail->quantity }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select class="form-select edit-status" required>
                                                <option value="Pending" {{ $detail->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Received" {{ $detail->status === 'Received' ? 'selected' : '' }}>Received</option>
                                                <option value="Cancelled" {{ $detail->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer custom-modal-footer pb-0">
                                        <x-primary-button type="button" class="btn btn-primary mb-4 update-product" data-detail-id="{{ $detail->supplierOrderDetailID }}">Update</x-primary-button>
                                        <x-secondary-button type="button" data-bs-dismiss="modal">Close</x-secondary-button>
                                    </div>
                                </x-modal>
                            @endforeach
                        </div>
                    </div>
                    <hr class="mb-3">
                    <x-primary-button type="submit" class="mt-4">
                        <span class="material-icons-outlined">save</span>
                        Update Order
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('.update-product')) {
                const modal = e.target.closest('.modal');
                const detailId = e.target.dataset.detailId;
                const card = document.querySelector(`.card[data-detail-id="${detailId}"]`);
                const quantity = parseInt(modal.querySelector('.edit-quantity').value);
                const unitCost = modal.querySelector('.edit-unitCost').value;
                const receivedQuantity = parseInt(modal.querySelector('.edit-receivedQuantity').value);
                let status = modal.querySelector('.edit-status').value;
    
                // Validate receivedQuantity
                if (receivedQuantity < 0 || receivedQuantity > quantity) {
                    alert('Received Quantity must be between 0 and the ordered Quantity.');
                    return;
                }
    
                // Auto-set status to Received if receivedQuantity equals quantity
                if (receivedQuantity === quantity) {
                    status = 'Received';
                }
    
                // Update card display
                card.querySelector('.text-start:nth-child(1) .fw-semibold').textContent = quantity;
                card.querySelector('.text-start:nth-child(2) .fw-semibold').textContent = `₱${parseFloat(unitCost).toFixed(2)}`;
                card.querySelector('.text-start:nth-child(3) .fw-semibold').textContent = receivedQuantity;
                const badge = card.querySelector('.badge');
                badge.textContent = status;
                badge.className = `badge bg-${status === 'Pending' ? 'warning' : (status === 'Received' ? 'success' : 'danger')}`;
    
                // Update hidden inputs
                card.querySelector('input[name$="[quantity]"]').value = quantity;
                card.querySelector('input[name$="[unitCost]"]').value = unitCost;
                card.querySelector('input[name$="[receivedQuantity]"]').value = receivedQuantity;
                card.querySelector('input[name$="[status]"]').value = status;
    
                bootstrap.Modal.getInstance(modal).hide();
            }
        });
    </script>
</x-app-layout>