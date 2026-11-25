@extends('BackEnd.Layouts.layout')

@section('title', 'User Packages')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">
                        User Packages
                    </h4>
                    <small class="text-muted">
                        Manage company and user package subscriptions
                    </small>
                </div>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('admin.company-package.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                        {{-- Company --}}
                        <div style="width: 220px;">
                            <select name="company_id" class="form-select select2 w-100">
                                <option value="">All Companies</option>

                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Search --}}
                        <div style="width: 280px;">
                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search User / Package / Company...">
                        </div>


                        {{-- Search Button --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>


                        {{-- Reset --}}
                        @if (request()->filled('search') || request()->filled('company_id'))
                            <a href="{{ route('admin.company-package.index') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i>
                                Reset
                            </a>
                        @endif

                    </form>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createPackageModal">
                        <i class="fa fa-plus-circle me-1"></i>
                        Assign Package
                    </button>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>Company</th>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Start Date</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($companyPackageGroups as $index => $group)
                                    @php
                                        $companyPackage = $group['latest'];
                                        $historyId = 'packageHistory' . $companyPackage->id;
                                    @endphp

                                    {{-- LATEST ASSIGNMENT --}}
                                    <tr>
                                        <td>{{ $companyPackageGroups->firstItem() + $index }}</td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $companyPackage->company?->name ?? 'N/A' }}
                                            </div>
                                        </td>

                                        <td>
                                            @if ($companyPackage->user)
                                                <div class="fw-semibold">
                                                    {{ $companyPackage->user->name }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $companyPackage->user->email }}
                                                </small>
                                            @else
                                                <span class="text-muted">All Users</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-semibold">
                                                {{ $companyPackage->package?->name ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $companyPackage->start_date?->format('d M Y') ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $companyPackage->expire_date?->format('d M Y') ?? 'No Expiry' }}
                                        </td>

                                        <td>
                                            @if ($companyPackage->display_status === 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif ($companyPackage->display_status === 'Expired')
                                                <span class="badge bg-danger">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif

                                            <div class="small text-muted mt-1">
                                                Latest Assignment
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-light border-0"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editPackageModal{{ $companyPackage->id }}">
                                                            <i class="fa fa-pencil me-2"></i>
                                                            Edit Latest
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <form
                                                            action="{{ route('admin.company-package.destroy', $companyPackage->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa fa-trash me-2"></i>
                                                                Delete Latest
                                                            </button>
                                                        </form>
                                                    </li>

                                                    @if ($group['total'] > 1)
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>

                                                        <li>
                                                            <button type="button" class="dropdown-item text-primary"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#{{ $historyId }}" aria-expanded="false"
                                                                aria-controls="{{ $historyId }}">
                                                                <i class="fa fa-history me-2"></i>
                                                                Previous Assignments
                                                                <span class="badge bg-primary ms-1">
                                                                    {{ $group['previous']->count() }}
                                                                </span>
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- HISTORY ROW --}}
                                    @if ($group['previous']->isNotEmpty())
                                        <tr>
                                            <td colspan="8" class="p-0 border-0">
                                                <div class="collapse" id="{{ $historyId }}">
                                                    <div class="bg-light p-3">
                                                        <div class="border rounded bg-white p-3">

                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-3">
                                                                <h6 class="mb-0">
                                                                    <i class="fa fa-history me-2 text-primary"></i>
                                                                    Previous Package Assignments
                                                                </h6>

                                                                <span class="badge bg-secondary">
                                                                    {{ $group['previous']->count() }} Records
                                                                </span>
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table table-sm table-bordered align-middle mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Package</th>
                                                                            <th>Start Date</th>
                                                                            <th>Expire Date</th>
                                                                            <th>Status</th>
                                                                            <th>Created At</th>
                                                                            <th class="text-center">Action</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        @foreach ($group['previous'] as $previous)
                                                                            <tr>
                                                                                <td>{{ $previous->id }}</td>

                                                                                <td>
                                                                                    {{ $previous->package?->name ?? 'N/A' }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $previous->start_date?->format('d M Y') ?? 'N/A' }}
                                                                                </td>

                                                                                <td>
                                                                                    {{ $previous->expire_date?->format('d M Y') ?? 'No Expiry' }}
                                                                                </td>

                                                                                <td>
                                                                                    @if ($previous->display_status === 'Active')
                                                                                        <span
                                                                                            class="badge bg-success">Active</span>
                                                                                    @endif
                                                                                    @if ($previous->display_status === 'Expired')
                                                                                        <span
                                                                                            class="badge bg-danger">Expired</span>
                                                                                    @endif
                                                                                    @if ($previous->display_status === 'Cancelled')
                                                                                        <span
                                                                                            class="badge bg-secondary">Cancelled</span>
                                                                                    @endif
                                                                                </td>

                                                                                <td>
                                                                                    {{ $previous->created_at?->format('d M Y h:i A') }}
                                                                                </td>

                                                                                <td class="text-center">
                                                                                    <div class="dropdown">
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-light border"
                                                                                            data-bs-toggle="dropdown">
                                                                                            <i class="fa fa-ellipsis-v"></i>
                                                                                        </button>

                                                                                        <ul
                                                                                            class="dropdown-menu dropdown-menu-end shadow">
                                                                                            <li>
                                                                                                <button type="button"
                                                                                                    class="dropdown-item"
                                                                                                    data-bs-toggle="modal"
                                                                                                    data-bs-target="#editPackageModal{{ $previous->id }}">
                                                                                                    <i
                                                                                                        class="fa fa-pencil me-2"></i>
                                                                                                    Edit
                                                                                                </button>
                                                                                            </li>

                                                                                            <li>
                                                                                                <form
                                                                                                    action="{{ route('admin.company-package.destroy', $previous->id) }}"
                                                                                                    method="POST"
                                                                                                    onsubmit="return confirm('Delete this previous assignment?')">
                                                                                                    @csrf
                                                                                                    @method('DELETE')

                                                                                                    <button type="submit"
                                                                                                        class="dropdown-item text-danger">
                                                                                                        <i
                                                                                                            class="fa fa-trash me-2"></i>
                                                                                                        Delete
                                                                                                    </button>
                                                                                                </form>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-boxes-stacked" style="font-size: 40px;"></i>
                                                <div class="mt-2">No packages assigned yet.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                    <div class="p-3">
                        {{ $companyPackageGroups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODALS FOR ALL ASSIGNMENTS --}}
    @foreach ($companyPackageGroups as $group)
        @foreach (collect([$group['latest']])->concat($group['previous']) as $companyPackage)
            <div class="modal fade" id="editPackageModal{{ $companyPackage->id }}" tabindex="-1" aria-hidden="true"
                data-bs-backdrop="static" data-bs-keyboard="false">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-pencil me-2"></i>
                                Update Package Assignment
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('admin.company-package.update', $companyPackage->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-body">

                                <div class="bg-light rounded p-3 mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted">Company</small>
                                            <div class="fw-semibold">
                                                {{ $companyPackage->company?->name ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">User</small>
                                            <div class="fw-semibold">
                                                {{ $companyPackage->user?->name ?? 'All Users' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">Package</small>
                                            <div class="fw-semibold">
                                                {{ $companyPackage->package?->name ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">Assignment ID</small>
                                            <div class="fw-semibold">
                                                #{{ $companyPackage->id }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ $companyPackage->start_date?->format('d M Y') }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Expire Date</label>
                                    <input type="date" name="expire_date" class="form-control"
                                        value="{{ $companyPackage->expire_date?->format('Y-m-d') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select select2" required>
                                        <option value="Active" @selected($companyPackage->status === 'Active')>
                                            Active
                                        </option>
                                        <option value="Expired" @selected($companyPackage->status === 'Expired')>
                                            Expired
                                        </option>
                                        <option value="Cancelled" @selected($companyPackage->status === 'Cancelled')>
                                            Cancelled
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i>
                                    Update Package
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- CREATE PACKAGE MODAL --}}
    <div class="modal fade" id="createPackageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-boxes-stacked me-1"></i>
                        Assign Package
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.company-package.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Company
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="company_id" id="company_id" class="form-select select2" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    User
                                </label>
                                <select name="user_id" id="user_id" class="form-select select2">
                                    <option value="">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" data-company="{{ $user->company_id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                            @if ($user->email)
                                                - {{ $user->email }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Leave empty if the package is for the company.
                                </small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Package
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="package_id" class="form-select select2" required>
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}"
                                            {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- START DATE --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Start Date
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ old('start_date', date('Y-m-d')) }}" required>
                            </div>
                            {{-- EXPIRE DATE --}}
                            <div class="col-md-6">
                                <label class="form-label">Expire Date</label>
                                <input type="date" name="expire_date" class="form-control"
                                    value="{{ old('expire_date') }}">
                                <small class="text-muted">
                                    Leave empty for no expiry.
                                </small>
                            </div>
                            {{-- STATUS --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Status
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select select2" required>
                                    <option value="Active">Active</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Assign Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const companySelect = document.getElementById('company_id');
            const userSelect = document.getElementById('user_id');
            if (companySelect && userSelect) {
                companySelect.addEventListener('change', function() {
                    const companyId = this.value;
                    Array.from(userSelect.options).forEach(function(option) {
                        if (!option.value) {
                            option.hidden = false;
                            return;
                        }
                        if (!companyId) {
                            option.hidden = false;
                        } else {
                            option.hidden =
                                option.dataset.company != companyId;
                        }
                    });
                    userSelect.value = '';
                });
            }
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (!confirm(
                            'Are you sure you want to delete this package assignment?'
                        )) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
