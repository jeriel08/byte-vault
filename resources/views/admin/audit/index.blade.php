<x-app-layout>
    <div class="container-fluid mx-auto px-4 py-6 position-relative">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mx-1 mb-4">
            <h1 class="h3 fw-semibold mb-0">Audit Log</h1>
            <x-secondary-button href="{{ route('orders.index') }}">
                <span class="material-icons-outlined">arrow_back</span>
                Back to Orders
            </x-secondary-button>
        </div>

        <!-- Audit Table -->
        <div class="card p-4 mx-1">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Employee</th>
                        <th>Action</th>
                        <th>Record</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td>{{ $log->logID }}</td>
                            <td>
                                {{ $log->employee ? $log->employee->firstName . ' ' . $log->employee->lastName : 'System' }}
                            </td>
                            <td>{{ ucfirst($log->actionType) }}</td>
                            <td>
                                {{ $tableNames[$log->tableName] ?? ucfirst(str_replace('_', ' ', $log->tableName)) }}
                            </td>
                            <td>{{ $log->timestamp->format('F j, Y') }}</td>
                            <td>
                                @if ($log->details->isNotEmpty())
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $log->log_id }}">
                                        View Details
                                    </button>
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
                            <span class="page-link">Previous</span>
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
                <div class="modal fade" id="detailsModal{{ $log->log_id }}" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel">Details for Record ID: {{ $log->record_id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if ($log->details->isNotEmpty())
                                    <ul>
                                        @foreach ($log->details as $detail)
                                            <li>
                                                <strong>{{ $detail->column_name }}:</strong>
                                                {{ $detail->old_value ?? 'N/A' }} → {{ $detail->new_value ?? 'N/A' }}
                                            </li>


                                        @endforeach
                                    </ul>
                                @else
                                    <p>No additional details available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            {{-- <div class="mt-4">
                {{ $auditLogs->links() }}
            </div> --}}
        </div>
    </div>
</x-app-layout>