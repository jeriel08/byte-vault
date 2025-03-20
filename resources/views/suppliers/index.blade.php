<x-app-layout>
    <div class="container">
        <h1>Suppliers</h1>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Add New Supplier</a>
    
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
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
</x-app-layout>