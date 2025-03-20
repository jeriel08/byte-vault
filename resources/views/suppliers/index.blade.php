<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <div class="d-flex justify-content-between mb-1">
            {{-- Filter Button --}}
            <x-secondary-button>
                <span class="material-icons-outlined">filter_alt</span>
                Filter
            </x-secondary-button>

            {{-- Add Supplier Button --}}
            <x-primary-button href="{{ route('suppliers.create') }}" class="mb-4 py-2">
                <span class="material-icons-outlined">add</span>
                Add New Supplier
            </x-primary-button>
        </div>
        
    
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="card account-manager-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->supplierID }}</td>
                                    <td>{{ $supplier->supplierName }}</td>
                                    <td>{{ $supplier->supplierAddress }}</td>
                                    <td>{{ $supplier->supplierPhoneNumber }}</td>
                                    <td>
                                        <a href="{{ route('suppliers.edit', $supplier->supplierID) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('suppliers.destroy', $supplier->supplierID) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>