<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="d-flex justify-content-end mb-1">
            <x-primary-button href="{{ route('categories.create') }}" class="mb-4 py-2">
                <span class="material-icons-outlined">add</span>
                Add Category
            </x-primary-button>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card account-settings-card">
            <div class="card-body">
                <div class="row">
                    @if ($categories->isEmpty())
                        <div class="col-12">
                            <div class="card account-manager-card text-center p-5">
                                <h5 class="text-muted d-flex justify-content-center align-items-center gap-3">
                                    No categories yet.
                                    <span class="material-icons-outlined fs-2">category</span>
                                </h5>
                            </div>
                        </div>
                    @else
                        @foreach ($categories as $category)
                            <div class="col-12 mb-3">
                                <div class="card account-manager-card p-3 d-flex flex-row align-items-center">
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="mb-1 fw-semibold">{{ $category->categoryName }}</h5>
                                        <p class="mb-1">{{ $category->categoryDescription ?? 'No description' }}</p>
                                        @if ($category->parent)
                                            <p class="mb-1 text-muted">Parent: {{ $category->parent->categoryName }}</p>
                                        @endif
                                        <span class="badge {{ $category->categoryStatus === 'Active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->categoryStatus }}
                                        </span>
                                    </div>
                                    <x-primary-button href="{{ route('categories.edit', $category->categoryID) }}">
                                        <span class="material-icons-outlined">edit</span>
                                        Edit
                                    </x-primary-button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>