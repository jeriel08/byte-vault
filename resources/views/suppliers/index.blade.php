<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <div class="d-flex justify-content-end mb-1">

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
        
        <div class="row">
            @if ($suppliers->isEmpty())
                <!-- No Suppliers Card -->
                <div class="col-12">
                    <div class="card account-manager-card text-center p-5">
                        <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                            There's no supplier yet. 
                            <span class="material-icons-outlined fs-2">
                                inventory_2 <!-- Supplier icon -->
                            </span>
                        </h5>
                    </div>
                </div>
            @else
                <!-- Supplier Cards -->
                @foreach ($suppliers as $supplier)
                    <div class="col-12 mb-3">
                        <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                            <img src="{{ asset('storage/' . $supplier->supplierProfileImage) }}" 
                                alt="{{ $supplier->supplierName }}" 
                                class="supplier-image rounded-circle me-3" 
                                style="width: 150px; height: 150px; object-fit: cover;" />
    
                            <!-- Supplier Details -->
                            <div class="flex-grow-1 ms-2">
                                <h5 class="mb-1">{{ $supplier->supplierName }}</h5>
                                <p class="mb-1">
                                    <strong>Address:</strong> {{ $supplier->supplierAddress }}<br />
                                    <strong>Phone:</strong> {{ $supplier->supplierPhoneNumber }}
                                </p>
                                <span class="badge {{ $supplier->supplierStatus == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier->supplierStatus }}
                                </span>
                            </div>
    
                            <!-- Buttons -->
                            <div class="d-flex flex-column gap-2">
                                <!-- Contact Button -->
                                <a href="tel:{{ $supplier->supplierPhoneNumber }}"
                                    class="primary-button d-flex justify-content-center align-items-center gap-2 px-5"> 
                                    <span class="material-icons-outlined">phone</span>
                                    Contact
                                </a>
    
                                <!-- Edit Button -->
                                <x-primary-button href="{{ route('suppliers.edit', $supplier->supplierID) }}" >
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