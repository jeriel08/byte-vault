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
                    <button type="submit">Save Adjustment</button>
                </form>    
            </div>    
        </div>    
    </div>
</x-app-layout>