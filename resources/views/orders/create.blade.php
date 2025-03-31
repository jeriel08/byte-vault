<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <h1 class="h3 fw-semibold mb-0">Add New Order</h1>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary py-2 d-flex align-items-center gap-1">
                <span class="material-icons-outlined">arrow_back</span>
                Back to Orders
            </a>
        </div>
        <div class="card account-manager-card p-4 mx-1">
            <p class="text-muted">POS System integration coming soon...</p>
        </div>
    </div>
</x-app-layout>
        