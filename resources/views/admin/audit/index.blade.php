<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <h1 class="h3 fw-semibold mb-0">Audit Log</h1>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary py-2 d-flex align-items-center gap-1">
                <span class="material-icons-outlined">arrow_back</span>
                Back to Orders
            </a>
        </div>

        <!-- Audit Table -->
        <div class="card p-4 mx-1">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Created At</th>
                        <th>Created By</th>
                        <th>Updated At</th>
                        <th>Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->orderID }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->createdBy ? $order->createdBy->firstName . ' ' . $order->createdBy->lastName : 'N/A' }}</td>
                            <td>{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i:s') : 'Not Updated' }}</td>
                            <td>{{ $order->updatedBy ? $order->updatedBy->firstName . ' ' . $order->updatedBy->lastName : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No audit logs available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>