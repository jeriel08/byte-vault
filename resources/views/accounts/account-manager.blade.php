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
        <div class="d-flex justify-content-between mb-1">
            {{-- Filter Button --}}
            <x-secondary-button>
                <span class="material-icons-outlined">filter_alt</span>
                Filter
            </x-secondary-button>

            {{-- Add Account Button --}}
            <x-primary-button href="{{ route('account.add') }}">
                <span class="material-icons-outlined">add</span>
                Add Account
            </x-primary-button>
        </div>

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover custom-table">
                    <thead>
                        <tr>
                            <th scope="col" class="py-2 px-4">Name</th>
                            <th scope="col" class="py-2 px-4">Username</th>
                            <th scope="col" class="py-2 px-4">Status</th>
                            <th scope="col" class="py-2 px-4">Role</th>
                            <th scope="col" class="py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="py-2 px-4">{{ $employee->firstName }} {{ $employee->lastName }}</td>
                                <td class="py-2 px-4">{{ $employee->username }}</td>
                                <td class="py-2 px-4">{{ $employee->status }}</td>
                                <td class="py-2 px-4">{{ $employee->role }}</td>
                                <td class="py-2 px-4">
                                    <x-primary-button href="{{ route('account.edit', $employee->employeeID) }}">
                                        <span class="material-icons-outlined">edit</span>
                                        Update
                                    </x-primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>