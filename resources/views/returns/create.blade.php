<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-2xl font-bold mb-4">Add Return to Supplier</h1>
            <x-secondary-button href="{{ route('returns.index') }}">
                <span class="material-icons-outlined">arrow_back</span>
                Go back
            </x-secondary-button>
        </div>
        
        <div class="card account-settings-card p-3">
            <div class="card-body">
                <form action="{{ route('returns.store') }}" method="POST" id="returnForm">
                    @csrf
                    <h5 class="fw-semibold mb-3">Return Information</h5>
                    <div class="mb-3">
                        <label for="supplierID" class="form-label fw-semibold">Supplier</label>
                        <select name="supplierID" id="supplierID" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplierID }}" {{ $order && $order->supplierID === $supplier->supplierID ? 'selected' : '' }}>
                                    {{ $supplier->supplierName }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplierID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="returnDate" class="form-label fw-semibold">Return Date</label>
                        <input type="date" name="returnDate" id="returnDate" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        @error('returnDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="returnSupplierReason" class="form-label fw-semibold">Return Reason</label>
                        <input type="text" name="returnSupplierReason" id="returnSupplierReason" class="form-control" placeholder="e.g., Defective items" required>
                        @error('returnSupplierReason') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <hr class="mb-4">
                    <div id="returnDetails" class="mb-3">
                        <h5 class="fw-semibold mb-3">Return Details</h5>
                        <x-secondary-button type="button" class="mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            Add Product
                        </x-secondary-button>
                        <div id="productList">
                            <!-- Dynamically added product cards will appear here -->
                            @if ($order)
                                @foreach ($order->details as $detail)
                                    <div class="card account-manager-card p-3 d-flex flex-row align-items-center mb-3">
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
                                            </div>
                                        </div>
                                        <div class="ms-5">
                                            <button type="button" class="btn btn-danger btn-sm remove-product">
                                                <span class="material-icons-outlined danger-badge fs-1">delete</span>
                                            </button>
                                        </div>
                                        <input type="hidden" name="details[0][productID]" value="{{ $detail->productID }}">
                                        <input type="hidden" name="details[0][quantity]" value="{{ $detail->quantity }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <hr class="mb-3">
                    <x-primary-button type="submit" class="mt-4">
                        <span class="material-icons-outlined">save</span>
                        Save Return
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Products -->
    <x-modal name="addProductModal" maxWidth="lg">
        <div class="modal-header custom-modal-header">
            <h5 class="modal-title" id="addProductModal-label">Add Product to Return</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body custom-modal-body">
            <div class="row d-flex justify-content-center align-content-center">
                <div class="row mb-3">
                    <label class="form-label fw-semibold">Product</label>
                    <select id="productID" class="form-select" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->productID }}">{{ $product->productName }} (Stock: {{ $product->stockQuantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-3">
                    <label class="form-label fw-semibold">Quantity</label>
                    <input type="number" id="quantity" class="form-control" min="1" required>
                </div>
            </div>
        </div>
        <div class="modal-footer custom-modal-footer py-2">
            <x-primary-button type="button" id="addProductBtn">Add Product</x-primary-button>
            <x-secondary-button type="button" data-bs-dismiss="modal">Close</x-secondary-button>
        </div>
    </x-modal>

    <!-- JavaScript for Managing Products -->
    <script>
        let index = {{ $order ? $order->details->count() : 0 }};
        document.getElementById('addProductBtn').addEventListener('click', function() {
            const productID = document.getElementById('productID').value;
            const quantity = document.getElementById('quantity').value;

            if (productID && quantity) {
                const productOptionText = document.querySelector(`#productID option[value="${productID}"]`).text;
                const productName = productOptionText.split(' (Stock:')[0]; // Extract only the name
                const productList = document.getElementById('productList');
                
                const productCard = document.createElement('div');
                productCard.className = 'card account-manager-card p-3 d-flex flex-row align-items-center mb-3';
                productCard.innerHTML = `
                    <div class="flex-grow-1">
                        <h5 class="mb-1 fw-semibold">${productName}</h5>
                    </div>
                    <div class="d-flex align-items-center mx-3">
                        <span class="vr me-3"></span>
                        <div class="d-flex flex-row gap-3 align-items-start">
                            <div class="text-start" style="min-width: 80px;">
                                <span class="text-muted d-block"><small>Quantity</small></span>
                                <span class="fw-semibold fs-5">${quantity}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ms-5">
                        <button type="button" class="btn btn-danger btn-sm remove-product">
                            <span class="material-icons-outlined danger-badge fs-1">delete</span>
                        </button>
                    </div>
                    <input type="hidden" name="details[${index}][productID]" value="${productID}">
                    <input type="hidden" name="details[${index}][quantity]" value="${quantity}">
                `;
                productList.appendChild(productCard);
                index++;

                bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
                document.getElementById('productID').value = '';
                document.getElementById('quantity').value = '';
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                e.target.closest('.card').remove();
            }
        });
    </script>
</x-app-layout>