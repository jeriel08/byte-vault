<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl fw-semibold mb-4">Edit Supplier</h1>
        <div class="card account-settings-card">
            <div class="card-body">
                <form action="{{ route('suppliers.update', $supplier->supplierID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="supplierName" class="form-label fw-semibold">Supplier Name</label>
                        <input type="text" class="form-control" id="supplierName" name="supplierName" value="{{ $supplier->supplierName }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplierAddress" class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" id="supplierAddress" name="supplierAddress" rows="3">{{ $supplier->supplierAddress }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="supplierPhoneNumber" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" class="form-control" id="supplierPhoneNumber" name="supplierPhoneNumber" value="{{ $supplier->supplierPhoneNumber }}">
                    </div>
                    <div class="my-4">
                        <label for="supplierProfileImage" class="form-label fw-semibold">Supplier Image</label>
                        <div class="file-upload-wrapper d-flex align-items-center">
                            <input type="file" name="supplierProfileImage" id="supplierProfileImage" class="file-input" accept="image/*">
                            <label for="supplierProfileImage" class="file-button">
                                <span class="material-icons-outlined">upload</span>
                                Choose File
                            </label>
                            <span class="file-name">No file chosen</span>
                        </div>
                        @if ($supplier->supplierProfileImage)
                            <img src="{{ asset('storage/' . $supplier->supplierProfileImage) }}" alt="Profile Image" class="supplier-image mt-3">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="supplierStatus" class="form-label fw-semibold">Status</label>
                        <select class="form-control" id="supplierStatus" name="supplierStatus">
                            <option value="Active" {{ $supplier->supplierStatus == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $supplier->supplierStatus == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <x-primary-button type="submit" class="mt-4">Update</x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('supplierProfileImage').addEventListener('change', function() {
            const fileName = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            document.querySelector('.file-name').textContent = fileName;
        });
    </script>
</x-app-layout>