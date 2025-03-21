<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="card account-settings-card">
            <div class="card-body">
                <form action="{{ route('brands.store') }}" method="POST">
                    @csrf
                    <input type="text" name="brandName" placeholder="Brand Name" class="form-control mb-3" required>
                    <select name="brandStatus" class="form-select mb-3" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <x-primary-button type="submit" class="mb-3">Add Brand</x-primary-button>
                </form>
            
                <ul>
                    @foreach($brands as $brand)
                        <li>{{ $brand->brandName }} ({{ $brand->brandStatus ? 'Active' : 'Inactive' }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>