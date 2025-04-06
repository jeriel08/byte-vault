<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl fw-semibold mb-4">Add New Employee Account</h1>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card account-manager-card">
            <div class="card-body">
                <form action="{{ route('account.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="firstName" class="form-label fw-semibold">First Name</label>
                        <input type="text" name="firstName" id="firstName" class="form-control" value="{{ old('firstName') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="lastName" id="lastName" class="form-control" value="{{ old('lastName') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{ old('phoneNumber') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                            <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <x-primary-button type="submit" class="mb-4">
                            Save Account
                        </x-primary-button>
                        <x-secondary-button href="{{ route('account.manager') }}">
                            Cancel
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>