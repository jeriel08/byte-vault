<x-app-layout>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Create Adjustment</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('adjustments.store') }}" method="POST">
                    @csrf
                    <div>
                        <label>Adjustment Date</label>
                        <input type="date" name="adjustmentDate" required>
                    </div>
                    <div>
                        <label>Adjustment Reason</label>
                        <input type="text" name="adjustmentReason" required placeholder="e.g., Damaged in storage">
                    </div>
                    <div id="products">
                        <div class="product-row">
                            <select name="products[0][productID]" required>
                                @if($products->isEmpty())
                                    <option value="">No active products available</option>
                                @else
                                    @foreach($products as $product)
                                        <option value="{{ $product->productID }}">{{ $product->name }} (Stock: {{ $product->stockQuantity }})</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="number" name="products[0][quantity]" min="1" required>
                        </div>
                    </div>
                    <button type="button" onclick="addProductRow()">Add Another Product</button>
                    <button type="submit">Save Adjustment</button>
                </form>   
            </div>    
        </div>    
    </div>

    <script>
        let rowCount = 1;
        function addProductRow() {
            const row = `<div class="product-row">
                <select name="products[${rowCount}][productID]" required>
                    @if($products->isEmpty())
                        <option value="">No active products available</option>
                    @else
                        {!! $products->map(fn($p) => "<option value=\"{$p->productID}\">{$p->name} (Stock: {$p->stockQuantity})</option>")->join('') !!}
                    @endif
                </select>
                <input type="number" name="products[${rowCount}][quantity]" min="1" required>
            </div>`;
            document.getElementById('products').insertAdjacentHTML('beforeend', row);
            rowCount++;
        }
    </script>
</x-app-layout>