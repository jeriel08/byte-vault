<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <div class="d-flex justify-content-end mb-1">
            {{-- Add Supplier Button --}}
            <x-primary-button href="{{ route('brands.create') }}" class="mb-4 py-2">
                <span class="material-icons-outlined">add</span>
                Add Brand
            </x-primary-button>
        </div>
        
    
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @if ($brands->isEmpty())
                <!-- No Brands Card -->
                <div class="col-12">
                    <div class="card account-manager-card text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            There's no brand yet.
                            <span class="material-icons-outlined fs-2">
                                inventory_2
                            </span>
                        </h5>
                    </div>
                </div>
            @else
                <!-- Brand Cards -->
                @foreach ($brands as $brand)
                    <div class="col-12 mb-3">
                        <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                            <img src="{{ $brand->brandProfileImage ? asset('storage/' . $brand->brandProfileImage) : asset('images/default-brand.png') }}"
                                alt="{{ $brand->brandName }}"
                                class="supplier-image rounded-circle me-3"
                                style="width: 150px; height: 150px; object-fit: cover;" />

                            <!-- Brand Details -->
                            <div class="flex-grow-1 ms-2">
                                <h5 class="mb-1 fw-semibold">{{ $brand->brandName }}</h5>
                                <span class="badge {{ $brand->brandStatus === 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $brand->brandStatus }}
                                </span>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex flex-column gap-2">
                                <x-primary-button href="{{ route('brands.edit', $brand->brandID) }}">
                                    <span class="material-icons-outlined">edit</span>
                                    Edit
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>