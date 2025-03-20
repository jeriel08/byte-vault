<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <div class="d-flex justify-content-between">
            {{-- Filter Button --}}
            <x-secondary-button>
                <span class="material-icons-outlined">filter_alt</span>
                Filter
            </x-secondary-button>

            {{-- Add Product Button --}}
            <x-primary-button>
                <span class="material-icons-outlined">add</span>
                Add Account
            </x-primary-button>
        </div>


        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover custom-table">
                    <thead>
                        <tr>
                            <th scope="col" class="py-2 px-4">Name</th>
                            <th scope="col" class="py-2 px-4">Username</th>
                            <th scope="col" class="py-2 px-4">Status</th>
                            <th scope="col" class="py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="py-2 px-4">{{ $employee->firstName }} {{ $employee->lastName }}</td>
                                <td class="py-2 px-4">{{ $employee->username }}</td>
                                <td class="py-2 px-4">{{ $employee->status }}</td>
                                <td class="py-2 px-4">
                                    <form action="{{ route('account.update', $employee->employeeID) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="password" name="password" placeholder="New Password" class="form-control" autocomplete="new-password">
                                        </div>
                                        <div class="mb-2">
                                            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" autocomplete="new-password">
                                        </div>
                                        <div class="mb-2">
                                            <select name="status" class="form-select">
                                                <option value="Active" {{ $employee->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ $employee->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <x-primary-button>
                                                Update
                                            </x-primary-button>
                                            <x-secondary-button type="button" onclick="window.history.back()">
                                                Cancel
                                            </x-secondary-button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>