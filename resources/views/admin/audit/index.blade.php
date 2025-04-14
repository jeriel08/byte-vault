<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header -->
        <div class="d-flex justify-content-end align-items-center mx-1 mb-4">
            <x-secondary-button type="button" data-bs-toggle="modal" data-bs-target="#filterAuditLogsModal">
                <span class="material-icons-outlined">filter_list</span>
                Filter Audit Logs
            </x-secondary-button>
        </div>

        <!-- Audit Table -->
        <div class="card p-4 mx-1">
            <table class="table table-striped inventory-table">
                <thead class="inventory-table-header">
                    <tr>
                        <th>Log ID</th>
                        <th>Employee</th>
                        <th>Action</th>
                        <th>Record</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="inventory-table-body table-group-divider">
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td class="align-middle">{{ $log->logID }}</td>
                            <td class="align-middle">
                                {{ $log->employee ? $log->employee->firstName . ' ' . $log->employee->lastName : 'System' }}
                            </td>
                            <td class="align-middle">{{ ucfirst($log->actionType) }}</td>
                            <td class="align-middle">
                                {{ $tableNames[$log->tableName] ?? ucfirst(str_replace('_', ' ', $log->tableName)) }}
                            </td>
                            <td class="align-middle">{{ $log->timestamp->format('F j, Y') }}</td>
                            <td class="align-middle">
                                @if ($log->details->isNotEmpty())
                                    <x-primary-button class="btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $log->logID }}">
                                        View Details
                                    </x-primary-button>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No audit logs available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                <ul class="pagination">
                    <!-- Previous Page Link -->
                    @if ($auditLogs->onFirstPage())
                        <li class="page-item disabled">
                            <span class="material-icons-outlined page-link">
                                navigate_before
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link d-flex justify-content-center align-items-center" href="{{ $auditLogs->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="prev">
                                <span class="material-icons-outlined">
                                    navigate_before
                                </span>
                            </a>
                        </li>
                    @endif

                    <!-- Page Numbers -->
                    @for ($i = 1; $i <= $auditLogs->lastPage(); $i++)
                        <li class="page-item {{ $auditLogs->currentPage() === $i ? 'active' : '' }}">
                            @if ($auditLogs->currentPage() === $i)
                                <span class="page-link">{{ $i }}</span>
                            @else
                                <a class="page-link" href="{{ $auditLogs->url($i) }}&{{ http_build_query(request()->except('page')) }}">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($auditLogs->hasMorePages())
                        <li class="page-item">
                            <a class="page-link d-flex justify-content-center align-items-center" href="{{ $auditLogs->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="next">
                                <span class="material-icons-outlined">
                                    navigate_next
                                </span>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="material-icons-outlined page-link">
                                navigate_next
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
            
            <!-- Modal for Details -->
            @foreach ($auditLogs as $log)
                <div class="modal fade" id="detailsModal{{ $log->logID }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $log->logID }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel{{ $log->logID }}">Audit Log Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <p><strong>Performed By:</strong> {{ $log->employee ? $log->employee->firstName . ' ' . $log->employee->lastName : 'System' }}</p>
                                    <p><strong>Date:</strong> {{ $log->timestamp->format('F j, Y \a\t H:i') }}</p>
                                    <p><strong>Entity Affected:</strong> 
                                        @php
                                            $friendlyName = $tableNames[$log->tableName] ?? ucwords(str_replace('_', ' ', $log->tableName));
                                            $routeName = $routeMap[$log->tableName] ?? null;
                                            $modelClass = '\App\Models\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $log->tableName)));
                                        @endphp
                                        @if ($routeName && in_array($log->actionType, ['create', 'update']) && class_exists($modelClass) && $modelClass::find($log->recordID))
                                            <a href="{{ route($routeName, $log->recordID) }}">{{ $friendlyName }}</a> (ID: {{ $log->recordID }})
                                        @else
                                            {{ $friendlyName }} (ID: {{ $log->recordID }})
                                        @endif
                                    </p>
                                </div>
                                <hr>
                                <div>
                                    <h6 class="@if ($log->actionType === 'create') text-success @elseif ($log->actionType === 'delete') text-danger @else text-primary @endif">
                                        @if ($log->actionType === 'create')
                                            <span class="material-icons-outlined align-middle">add</span> Created Data
                                        @elseif ($log->actionType === 'delete')
                                            <span class="material-icons-outlined align-middle">delete</span> Deleted Data
                                        @else
                                            <span class="material-icons-outlined align-middle">edit</span> Changes
                                        @endif
                                    </h6>
                                    @if ($log->details->isNotEmpty())
                                        <ul class="list-unstyled">
                                            @foreach ($log->details as $detail)
                                                <li class="@if ($log->actionType === 'create') text-success @elseif ($log->actionType === 'delete') text-danger @else text-primary @endif">
                                                    @if ($log->actionType === 'update')
                                                        <strong>{{ ucwords(str_replace('_', ' ', $detail->columnName)) }}:</strong>
                                                        {{ $detail->oldValue ?? 'N/A' }} → {{ $detail->newValue ?? 'N/A' }}
                                                    @elseif ($log->actionType === 'create' && in_array($detail->columnName, ['created', 'created_record']))
                                                        @php
                                                            $data = json_decode($detail->newValue, true);
                                                            if (is_array($data)) {
                                                                foreach ($data as $key => $value) {
                                                                    if (strpos($key, 'Date') !== false || strpos($key, '_at') !== false) {
                                                                        $date = \Carbon\Carbon::parse($value);
                                                                        echo '<li><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . $date->format('F j, Y \a\t H:i') . '</li>';
                                                                    } else {
                                                                        echo '<li><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . e($value) . '</li>';
                                                                    }
                                                                }
                                                            } else {
                                                                echo '<li>' . ($detail->newValue ?? 'N/A') . '</li>';
                                                            }
                                                        @endphp
                                                    @elseif ($log->actionType === 'delete' && in_array($detail->columnName, ['deleted', 'deleted_record']))
                                                        @php
                                                            $data = json_decode($detail->oldValue, true);
                                                            $excludeFields = ['created_at', 'updated_at'];
                                                            if (is_array($data)) {
                                                                foreach ($data as $key => $value) {
                                                                    if (!in_array($key, $excludeFields)) {
                                                                        echo '<li><strong>' . ucwords(str_replace('_', ' ', $key)) . ':</strong> ' . e($value) . '</li>';
                                                                    }
                                                                }
                                                            } else {
                                                                echo '<li>' . ($detail->oldValue ?? 'N/A') . '</li>';
                                                            }
                                                        @endphp
                                                    @else
                                                        <strong>{{ ucwords(str_replace('_', ' ', $detail->columnName)) }}:</strong>
                                                        {{ $log->actionType === 'create' ? ($detail->newValue ?? 'N/A') : ($detail->oldValue ?? 'N/A') }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No field changes recorded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Updated Filter Modal -->
    <x-modal name="filterAuditLogsModal" maxWidth="lg">
        <div class="modal-header">
            <h5 class="modal-title" id="filterAuditLogsModal-label">Filter Audit Logs</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="auditLogFilterForm" action="{{ route('audit.index') }}" method="GET">
                <!-- User Name (Searchable Dropdown) -->
                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id[]" id="user_id" class="form-select select2" multiple>
                        <option value="">Select User(s)</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->employeeID }}" {{ in_array($user->employeeID, request()->input('user_id', [])) ? 'selected' : '' }}>
                                {{ $user->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range Picker -->
                <div class="mb-3">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" name="date_range" id="date_range" class="form-control flatpickr" value="{{ request()->input('date_range') }}" placeholder="Select date range">
                </div>

                <!-- Action Types (Multi-Select) -->
                <div class="mb-3">
                    <label for="action_type" class="form-label">Action Type</label>
                    <select name="action_type[]" id="action_type" class="form-select select2" multiple>
                        <option value="login" {{ in_array('login', request()->input('action_type', [])) ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ in_array('logout', request()->input('action_type', [])) ? 'selected' : '' }}>Logout</option>
                        <option value="create" {{ in_array('create', request()->input('action_type', [])) ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ in_array('update', request()->input('action_type', [])) ? 'selected' : '' }}>Update</option>
                    </select>
                </div>

                <!-- Table Names (Multi-Select) -->
                <div class="mb-3">
                    <label for="table_name" class="form-label">Table Name</label>
                    <select name="table_name[]" id="table_name" class="form-select select2" multiple>
                        @foreach($tableNames as $key => $label)
                            <option value="{{ $key }}" {{ in_array($key, request()->input('table_name', [])) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset</button>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('auditLogFilterForm').submit()">Apply Filters</button>
        </div>
    </x-modal>

<script>
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
    });

    // Initialize Flatpickr for Date Range
    flatpickr('#date_range', {
        mode: 'range',
        dateFormat: 'Y-m-d',
        defaultDate: '{{ request()->input('date_range') }}'
    });

    // Reset Filters
    function resetFilters() {
        document.getElementById('auditLogFilterForm').reset();
        $('.select2').val(null).trigger('change'); // Clear Select2 selections
        flatpickr('#date_range').clear(); // Clear Flatpickr
        window.location = '{{ route('audit.index') }}'; // Redirect to clear query params
    }
</script>
</x-app-layout>