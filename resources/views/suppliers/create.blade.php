<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl fw-semibold mb-4">Add New Supplier</h1>
        <div class="card account-settings-card">
            <div class="card-body">
                <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="supplierName" class="form-label fw-semibold">Supplier Name</label>
                        <input type="text" class="form-control" id="supplierName" name="supplierName" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplierAddress" class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" id="supplierAddress" name="supplierAddress" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="supplierPhoneNumber" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control" id="supplierPhoneNumber" name="supplierPhoneNumber">
                    </div>
                    <div class="mb-3">
                        <label for="supplierStatus" class="form-label fw-semibold">Status</label>
                        <select class="form-control" id="supplierStatus" name="supplierStatus" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="supplierProfileImage" class="form-label fw-semibold">Profile Image</label>
                        <input type="file" class="form-control" id="supplierProfileImage" name="supplierProfileImage">
                    </div>
                    <x-primary-button type="submit" class="mt-4">Submit</x-primary-button>
                </form> 
            </div>
        </div>
    </div>
</x-app-layout>