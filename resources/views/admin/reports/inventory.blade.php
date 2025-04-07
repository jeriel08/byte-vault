<x-app-layout>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Inventory report as of {{ \Carbon\Carbon::now()->format('F j, Y') }}</h2>
            <div class="d-flex gap-2">
                <x-primary-button href="{{ route('reports.inventory.download.excel') }}" class="me-2">
                    <i class="fa-solid fa-file-excel"></i> Download Excel
                </x-primary-button> 
                <x-primary-button href="{{ route('reports.inventory.download.pdf') }}" class="me-2">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                </x-primary-button> 
                <x-primary-button href="{{ route('dashboard') }}">
                    <span class="material-icons-outlined">
                        arrow_back
                    </span>Go back
                </x-primary-button>
            </div>
        </div>
        <hr class="mb-4">
        
        <!-- Summary Stats -->
        <div class="mb-4">
            <p><strong>Total Products:</strong> {{ $totalProducts }}</p>
            <p><strong>Total Inventory Value:</strong> ${{ number_format($totalValue, 2) }}</p>
            <p><strong>Low Stock Items (Stock < 5):</strong> {{ $lowStockCount }}</p>
        </div>
    
        <!-- Products Table -->
        <table class="table table-bordered inventory-table">
            <thead class="inventory-table-header">
                <tr>
                    <th class="text-center">ID</th>
                    <th>Product Name</th>
                    <th>Stock</th>
                    <th>Unit Price</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody class="inventory-table-body table-group-divider">
                @foreach ($products as $product)
                    <tr class="inventory-table-row">
                        <td class="text-center">{{ $product->productID }}</td>
                        <td>{{ $product->productName }}</td>
                        <td class="text-end">{{ $product->stockQuantity }}</td>
                        <td class="text-end">₱{{ number_format($product->price, 2) }}</td>
                        <td class="text-end">₱{{ number_format($product->stockQuantity * $product->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>