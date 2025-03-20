<x-app-layout>
    <div class="container">
        <h1>Edit Supplier</h1>
        <form action="{{ route('suppliers.update', $supplier->supplierID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="supplierName" class="form-label">Supplier Name</label>
                <input type="text" class="form-control" id="supplierName" name="supplierName" value="{{ $supplier->supplierName }}" required>
            </div>
            <div class="mb-3">
                <label for="supplierAddress" class="form-label">Address</label>
                <textarea class="form-control" id="supplierAddress" name="supplierAddress" rows="3">{{ $supplier->supplierAddress }}</textarea>
            </div>
            <div class="mb-3">
                <label for="supplierPhoneNumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="supplierPhoneNumber" name="supplierPhoneNumber" value="{{ $supplier->supplierPhoneNumber }}">
            </div>
            <div class="mb-3">
                <label for="supplierProfileImage" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="supplierProfileImage" name="supplierProfileImage">
                @if ($supplier->supplierProfileImage)
                    <img src="{{ asset('storage/' . $supplier->supplierProfileImage) }}" alt="Profile Image" width="100" class="mt-2">
                @endif
            </div>
            <div class="mb-3">
                <label for="supplierStatus" class="form-label">Status</label>
                <select class="form-control" id="supplierStatus" name="supplierStatus">
                    <option value="Active" {{ $supplier->supplierStatus == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $supplier->supplierStatus == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</x-app-layout>