@extends('BackEnd.Layouts.layout')

@section('title', 'Activity Logs')

@section('content')
    <div class="p-4">
        <div class="mt-5">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1"><i class="fa-solid fa-user-check me-2"></i> Activity Logs</h4>
                    <small class="text-muted">
                        Monitor user activities and system changes
                    </small>
                </div>
            </div>
            {{-- Filters --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <form action="{{ route('admin.activity-log.index') }}" method="GET">
                        <div class="row g-3">

                            {{-- Search --}}
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search activity or user">
                            </div>

                            {{-- User --}}
                            <div class="col-md-2">
                                <label class="form-label">User</label>
                                <select name="user_id" class="form-select select2">
                                    <option value="">All Users</option>

                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Module --}}
                            <div class="col-md-2">
                                <label class="form-label">Module</label>
                                <select name="module" class="form-select select2">
                                    <option value="">All Modules</option>

                                    @foreach ([
            'system' => 'System',
            'authentication' => 'Authentication',
            'company' => 'Company',
            'branch' => 'Branch',
            'user' => 'User',
            'product' => 'Product',
            'package' => 'Package',
            'companypackage' => 'Company Package',
            'receipt' => 'Receipt',
            'receiptitem' => 'Receipt Item',
            'party' => 'Party',
            'sales' => 'Sales',
            'purchase' => 'Purchase',
            'inventory' => 'Inventory',
            'stocktransaction' => 'Stock Transaction',
            'accounts' => 'Accounts',
            'accounttransaction' => 'Account Transaction',
        ] as $key => $value)
                                        <option value="{{ $key }}" @selected(request('module') == $key)>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Event --}}
                            <div class="col-md-2">
                                <label class="form-label">Event</label>
                                <select name="event" class="form-select select2">
                                    <option value="">All Events</option>

                                    @foreach ([
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'registered' => 'Registered',
            'login' => 'Login',
            'logout' => 'Logout',
            'failed_login' => 'Failed Login',
            'custom' => 'Custom',
        ] as $key => $value)
                                        <option value="{{ $key }}" @selected(request('event') == $key)>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- From Date --}}
                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="form-control">
                            </div>

                            {{-- To Date --}}
                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                            </div>

                            {{-- Buttons --}}
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                    Filter
                                </button>

                                <a href="{{ route('admin.activity-log.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh me-1"></i>
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            {{-- Activity Table --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Module</th>
                                    <th>Event</th>
                                    <th>IP Address</th>
                                    <th>Date & Time</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $index => $activity)
                                    @php
                                        $event = strtolower($activity->event ?? 'custom');
                                        $badgeClass = match ($event) {
                                            'created' => 'bg-success',
                                            'updated' => 'bg-warning text-dark',
                                            'deleted' => 'bg-danger',
                                            'login' => 'bg-primary',
                                            'logout' => 'bg-secondary',
                                            default => 'bg-info text-dark',
                                        };
                                        $properties = $activity->properties ?? collect();
                                        $changes = $activity->attribute_changes ?? [];
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $activities->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $activity->causer?->name ?? 'System / Guest' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $activity->causer?->email ?? '' }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ $activity->description }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst($activity->log_name ?? 'System') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($event) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ data_get($properties, 'ip_address', 'N/A') }}
                                        </td>
                                        <td>
                                            {{ $activity->created_at?->format('d M Y h:i A') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#activityModal{{ $activity->id }}">
                                                <i class="fa fa-eye"></i>
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- Details Modal --}}
                                    <div class="modal fade" id="activityModal{{ $activity->id }}" tabindex="-1"
                                        aria-hidden="true">

                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fa-solid fa-user-check me-2"></i> Activity Details
                                                        #{{ $activity->id }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <strong>User</strong>
                                                            <div>
                                                                {{ $activity->causer?->name ?? 'System / Guest' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <strong>Activity</strong>
                                                            <div>{{ $activity->description }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <strong>IP Address</strong>
                                                            <div>
                                                                {{ data_get($properties, 'ip_address', 'N/A') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <strong>Date</strong>
                                                            <div>
                                                                {{ $activity->created_at?->format('d M Y h:i:s A') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <strong>URL</strong>
                                                            <div class="text-break">
                                                                {{ data_get($properties, 'url', 'N/A') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <strong>Branch ID</strong>
                                                            <div>
                                                                {{ data_get($properties, 'branch_id', 'N/A') }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <h6>Changed Attributes</h6>

                                                    <pre class="bg-light rounded p-3 small" style="max-height: 300px; overflow:auto;">{{ json_encode($changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                                                    <h6>Additional Properties</h6>

                                                    <pre class="bg-light rounded p-3 small" style="max-height: 250px; overflow:auto;">{{ json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="fa fa-times me-1"></i> Close
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            No activity found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
