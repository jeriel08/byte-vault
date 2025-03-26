<x-app-layout>
    <div class="container">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <h1>Adjustments</h1>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Total Quantity</th>
                        <th>Products</th>
                    </tr>
                    @foreach($adjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->adjustmentDate }}</td>
                            <td>{{ $adjustment->adjustmentReason }}</td>
                            <td>{{ $adjustment->stockOut->totalQuantity ?? 'N/A' }}</td>
                            <td>
                                @if($adjustment->stockOut && $adjustment->stockOut->details)
                                    @foreach($adjustment->stockOut->details as $detail)
                                        {{ $detail->product->name }}: {{ $detail->quantity }}<br>
                                    @endforeach
                                @else
                                    No products recorded
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <x-primary-button href="{{ route('adjustments.create') }}">New Adjustment</x-primary-button>
            </div>
        </div>
    </div>
</x-app-layout>