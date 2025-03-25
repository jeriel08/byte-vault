<x-app-layout>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1>Adjustments</h1>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                    </tr>
                    @foreach($adjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->adjustmentDate }}</td>
                            <td>{{ $adjustment->adjustmentReason }}</td>
                        </tr>
                    @endforeach
                </table>
                <x-primary-button href="{{ route('adjustments.create') }}">New Adjustment</x-primary-button>
            </div>
        </div>
    </div>
</x-app-layout>