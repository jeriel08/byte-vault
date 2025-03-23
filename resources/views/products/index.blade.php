<x-app-layout>
    <div class="container-fluid py-6 position-relative">
        <!-- Header with Search and Add Product -->
        <div class="d-flex justify-content-between align-items-center mx-4 mb-4">
            <div class="input-group w-50">
                <input type="text" class="search-input" placeholder="Search by product name" aria-label="Search products">
                <button class="search-button d-flex align-items-center justify-content-center" type="button">
                    <span class="material-icons-outlined">search</span>
                </button>
            </div>
            <x-primary-button href="{{ route('products.create') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                Add Product
            </x-primary-button>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4 mx-4">{{ session('success') }}</div>
        @endif

        <div class="row mx-2">
            <!-- Static Filter Panel -->
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card filter-panel h-100">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">Filter Products</h5>
                        
                        <!-- Product Status -->
                        <label class="fw-semibold mb-2">Product Status</label>
                        <div class="btn-group d-flex flex-wrap gap-2 mb-3" role="group">
                            <button type="button" class="btn category-filter-button flex-grow-1">
                                <span class="badge me-2">{{ $products->count() }}</span> All
                            </button>
                            <button type="button" class="btn category-filter-button flex-grow-1">
                                <span class="badge me-2">{{ $products->where('productStatus', 'Active')->count() }}</span> Active
                            </button>
                            <button type="button" class="btn category-filter-button flex-grow-1">
                                <span class="badge me-2">{{ $products->where('productStatus', 'Inactive')->count() }}</span> Inactive
                            </button>
                            <button type="button" class="btn category-filter-button flex-grow-1">
                                <span class="badge me-2">0</span> Draft
                            </button>
                        </div>

                        <hr>

                        <!-- Sort By -->
                        <label class="fw-semibold mb-2">Sort By</label>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortBy" id="priceAsc" value="price_asc">
                                <label class="form-check-label" for="priceAsc">Price: Low to High</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortBy" id="priceDesc" value="price_desc">
                                <label class="form-check-label" for="priceDesc">Price: High to Low</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortBy" id="nameAsc" value="name_asc">
                                <label class="form-check-label" for="nameAsc">Name: A-Z</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortBy" id="nameDesc" value="name_desc">
                                <label class="form-check-label" for="nameDesc">Name: Z-A</label>
                            </div>
                        </div>

                        <hr>

                        <!-- Category -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-semibold">Category</label>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm d-flex justify-content-center align-items-center">
                                <span class="material-icons-outlined">settings</span>
                            </a>
                        </div>
                        <div class="accordion mb-3" id="categoryAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="categoryHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryCollapse">
                                        Select Categories
                                    </button>
                                </h2>
                                <div id="categoryCollapse" class="accordion-collapse collapse show" aria-labelledby="categoryHeading" data-bs-parent="#categoryAccordion">
                                    <div class="accordion-body">
                                        @foreach ($products->pluck('category')->unique('categoryID') as $category)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="category[]" id="category{{ $category->categoryID }}" value="{{ $category->categoryID }}">
                                                <label class="form-check-label" for="category{{ $category->categoryID }}">{{ $category->categoryName }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Brand -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-semibold">Brand</label>
                            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary btn-sm d-flex justify-content-center align-items-center">
                                <span class="material-icons-outlined">settings</span>
                            </a>
                        </div>
                        <div class="accordion mb-3" id="brandAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="brandHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#brandCollapse" aria-expanded="true" aria-controls="brandCollapse">
                                        Select Brands
                                    </button>
                                </h2>
                                <div id="brandCollapse" class="accordion-collapse collapse show" aria-labelledby="brandHeading" data-bs-parent="#brandAccordion">
                                    <div class="accordion-body">
                                        @foreach ($products->pluck('brand')->unique('brandID') as $brand)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="brand[]" id="brand{{ $brand->brandID }}" value="{{ $brand->brandID }}">
                                                <label class="form-check-label" for="brand{{ $brand->brandID }}">{{ $brand->brandName }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Reset Filters -->
                        <button class="btn btn-outline-danger w-100">Reset Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>