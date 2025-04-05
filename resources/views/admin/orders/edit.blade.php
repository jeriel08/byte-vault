<x-app-layout>
    <div class="orders-container">
        <div class="main-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Order</h1>
            <div class="user-profile">
                <div class="user-avatar">
                    <span class="material-icons">person</span>
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Admin</div>
                </div>
                <span class="material-icons">expand_more</span>
            </div>
        </div>

        <div class="orders-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="orders-header">Edit Order #{{ $order->order_id }}</h2>
                <a href="{{ route('orders.index') }}" class="btn filter-btn">
                    <span class="material-icons-outlined align-middle">arrow_back</span>
                    Back to Orders
                </a>
            </div>

            <form action="{{ route('orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                               id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="product_id" class="form-label">Product</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id">
                            <option value="">Select Product</option>
                            @foreach(App\Models\Product::all() as $product)
                                <option value="{{ $product->id }}" 
                                        {{ old('product_id', $order->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" name="quantity" value="{{ old('quantity', $order->quantity) }}">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" value="{{ old('amount', $order->amount) }}">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" 
                                id="payment_status" name="payment_status">
                            <option value="Pending" {{ old('payment_status', $order->payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ old('payment_status', $order->payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Failed" {{ old('payment_status', $order->payment_status) == 'Failed' ? 'selected' : '' }}>Failed</option>
                            <option value="Refunded" {{ old('payment_status', $order->payment_status) == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="status" class="form-label">Order Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status">
                            <option value="Pending" {{ old('status', $order->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ old('status', $order->status) == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ old('status', $order->status) == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ old('status', $order->status) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ old('status', $order->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>