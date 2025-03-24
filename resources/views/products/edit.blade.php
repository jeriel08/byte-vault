<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Edit Product</h1>
        <div class="card account-settings-card">
            <div class="card-body">
                <form action="{{ route('products.update', $product->productID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="productName" class="form-label fw-semibold">Product Name</label>
                        <input type="text" name="productName" id="productName" class="form-control" value="{{ $product->productName }}" required>
                        @error('productName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label fw-semibold">Description</label>
                        <textarea name="productDescription" id="productDescription" class="form-control">{{ $product->productDescription }}</textarea>
                        @error('productDescription') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="brandID" class="form-label fw-semibold">Brand</label>
                        <select name="brandID" id="brandID" class="form-select" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->brandID }}" {{ $product->brandID == $brand->brandID ? 'selected' : '' }}>
                                    {{ $brand->brandName }}
                                </option>
                            @endforeach
                        </select>
                        @error('brandID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categoryID" class="form-label fw-semibold">Category</label>
                        <select name="categoryID" id="categoryID" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->categoryID }}" {{ $product->categoryID == $category->categoryID ? 'selected' : '' }}>
                                    {{ $category->categoryName }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoryID') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="productStatus" class="form-label fw-semibold">Status</label>
                        <select name="productStatus" id="productStatus" class="form-select" required>
                            <option value="Active" {{ $product->productStatus === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $product->productStatus === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('productStatus') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <x-primary-button type="submit" class="mt-4">
                        <span class="material-icons-outlined">save</span>
                        Update Product
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>