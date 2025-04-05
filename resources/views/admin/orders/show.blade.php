<!-- resources/views/orders/show.blade.php -->
<x-app-layout>
    <div class="orders-container">
        <div class="main-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Order Details</h1>
            <div class="user-profile">
                <div class="user-avatar">
                    <span class="material-icons">person</span>
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</div>
                    <div class="user-role">{{ Auth::user()->role }}</div>
                </div>
                <span class="material-icons">expand_more</span>
            </div>
        </div>

        <div class="orders-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="orders-header">Order #{{ $order->order_id }}</h2>
                <a href="{{ route('orders.index') }}" class="btn filter-btn">
                    <span class="material-icons-outlined align-middle">arrow_back</span>
                    Back to Orders
                </a>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Order Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>{{ $order->order_id }}</td>
                                </tr>
                                <tr>
                                    <th>Customer:</th>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td><span class="status-badge status-{{ strtolower($order->status) }}">{{ $order->status }}</span></td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>{{ $order->payment_status }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($order->product && $order->product->image)
                                    <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" class="product-img me-3" style="width: 60px; height: 60px;">
                                @else
                                    <span class="material-icons-outlined me-3" style="font-size: 60px;">inventory_2</span>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $order->product ? $order->product->name : 'N/A' }}</h5>
                                    @if($order->product)
                                        <p class="text-muted mb-0">{{ $order->product->sku ?? 'No SKU' }}</p>
                                    @endif
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Quantity:</th>
                                    <td>{{ $order->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Unit Price:</th>
                                    <td>₱{{ number_format($order->product ? $order->product->price : 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>₱{{ number_format($order->amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary me-2">
                    <span class="material-icons-outlined align-middle">edit</span>
                    Edit Order
                </a>
                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
                        <span class="material-icons-outlined align-middle">delete</span>
                        Delete Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>