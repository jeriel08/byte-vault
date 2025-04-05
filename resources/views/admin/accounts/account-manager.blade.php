<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Buttons -->
        <div class="d-flex justify-content-between mb-4">
            {{-- Filter Button --}}
            <x-secondary-button>
                <span class="material-icons-outlined">filter_alt</span>
                Filter
            </x-secondary-button>

            {{-- Add Account Button --}}
            <x-primary-button href="{{ route('account.add') }}" class="py-2">
                <span class="material-icons-outlined">add</span>
                Add Account
            </x-primary-button>
        </div>

        <!-- Employee Table -->
        <div class="card account-manager-card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover custom-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="align-middle">{{ $employee->firstName }} {{ $employee->lastName }}</td>
                                    <td class="align-middle">{{ $employee->email }}</td>
                                    <td class="align-middle">{{ $employee->status }}</td>
                                    <td class="align-middle">{{ $employee->role }}</td>
                                    <td class="align-middle">
                                        <x-primary-button href="{{ route('account.edit', $employee->employeeID) }}">
                                            <span class="material-icons-outlined">edit</span>
                                        </x-primary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>