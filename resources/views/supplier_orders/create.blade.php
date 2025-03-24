<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Create New Supplier Order</h1>
        <div class="card account-settings-card">
            <div class="card-body">
                <form action="{{ route('supplier_orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="supplierID" class="form-label fw-semibold">Supplier</label>
                        <select name="supplierID" id="supplierID" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplierID }}">{{ $supplier->supplierName }}</option>
                            @endforeach
                        </select>
                        @error('supplierID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="orderDate" class="form-label fw-semibold">Order Date</label>
                        <input type="date" name="orderDate" id="orderDate" class="form-control" required>
                        @error('orderDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expectedDeliveryDate" class="form-label fw-semibold">Expected Delivery Date</label>
                        <input type="date" name="expectedDeliveryDate" id="expectedDeliveryDate" class="form-control">
                        @error('expectedDeliveryDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dynamic Order Details -->
                    <div id="orderDetails">
                        <h5 class="fw-semibold mb-3">Order Details</h5>
                        <div class="detail-row mb-3" data-index="0">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Product</label>
                                    <select name="details[0][productID]" class="form-select" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->productID }}">{{ $product->productName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="number" name="details[0][quantity]" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Unit Cost</label>
                                    <input type="number" name="details[0][unitCost]" class="form-control" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-detail">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addDetail" class="btn btn-secondary mb-3">Add Item</button>

                    <x-primary-button type="submit" class="mt-4">
                        <span class="material-icons-outlined">save</span>
                        Save Order
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Rows -->
    <script>
        let index = 1;
        document.getElementById('addDetail').addEventListener('click', function() {
            const container = document.getElementById('orderDetails');
            const newRow = document.createElement('div');
            newRow.className = 'detail-row mb-3';
            newRow.dataset.index = index;
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Product</label>
                        <select name="details[${index}][productID]" class="form-select" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->productID }}">{{ $product->productName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="number" name="details[${index}][quantity]" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Unit Cost</label>
                        <input type="number" name="details[${index}][unitCost]" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-detail">Remove</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            index++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-detail')) {
                e.target.closest('.detail-row').remove();
            }
        });
    </script>
</x-app-layout>