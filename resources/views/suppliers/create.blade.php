<x-app-layout>
    <div class="container">
        <h1>Add New Supplier</h1>
        <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="supplierName" class="form-label">Supplier Name</label>
                <input type="text" class="form-control" id="supplierName" name="supplierName" required>
            </div>
            <div class="mb-3">
                <label for="supplierAddress" class="form-label">Address</label>
                <textarea class="form-control" id="supplierAddress" name="supplierAddress" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="supplierPhoneNumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="supplierPhoneNumber" name="supplierPhoneNumber">
            </div>
            <div class="mb-3">
                <label for="supplierStatus" class="form-label">Status</label>
                <select class="form-control" id="supplierStatus" name="supplierStatus" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="supplierProfileImage" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="supplierProfileImage" name="supplierProfileImage">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</x-app-layout>