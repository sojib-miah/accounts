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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPackageModal">
                    <i class="fa fa-plus-circle me-1"></i>
                    Assign Package
                </button>
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
                                @forelse($companyPackages as $index => $companyPackage)
                                    @php
                                        $displayStatus = $companyPackage->display_status;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                                                @if ($companyPackage->user->email)
                                                    <small class="text-muted">
                                                        {{ $companyPackage->user->email }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    All Users
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($companyPackage->package)
                                                <span class="fw-semibold">
                                                    {{ $companyPackage->package->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($companyPackage->start_date)->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if ($companyPackage->expire_date)
                                                {{ $companyPackage->expire_date->format('d M Y') }}
                                            @else
                                                <span class="text-muted">No Expiry</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($displayStatus === 'Active')
                                                <span class="badge bg-success">
                                                    Active
                                                </span>
                                            @elseif($displayStatus === 'Expired')
                                                <span class="badge bg-danger">
                                                    Expired
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editPackageModal{{ $companyPackage->id }}"
                                                    title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                {{-- DELETE --}}
                                                <form
                                                    action="{{ route('admin.company-package.destroy', $companyPackage->id) }}"
                                                    method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{--  EDIT MODAL --}}
                                    <div class="modal fade" id="editPackageModal{{ $companyPackage->id }}" tabindex="-1"
                                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Update Package
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form
                                                    action="{{ route('admin.company-package.update', $companyPackage->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="bg-light rounded p-3 mb-3">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <small class="text-muted">
                                                                        Company
                                                                    </small>
                                                                    <div class="fw-semibold">
                                                                        {{ $companyPackage->company?->name ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <small class="text-muted">
                                                                        Package
                                                                    </small>
                                                                    <div class="fw-semibold">
                                                                        {{ $companyPackage->package?->name ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Start Date
                                                            </label>
                                                            <input type="text" class="form-control"
                                                                value="{{ optional($companyPackage->start_date)->format('d M Y') }}"
                                                                readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Expire Date
                                                            </label>
                                                            <input type="date" name="expire_date" class="form-control"
                                                                value="{{ optional($companyPackage->expire_date)->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Status
                                                            </label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="Active"
                                                                    {{ $companyPackage->status === 'Active' ? 'selected' : '' }}>
                                                                    Active
                                                                </option>
                                                                <option value="Expired"
                                                                    {{ $companyPackage->status === 'Expired' ? 'selected' : '' }}>
                                                                    Expired
                                                                </option>
                                                                <option value="Cancelled"
                                                                    {{ $companyPackage->status === 'Cancelled' ? 'selected' : '' }}>
                                                                    Cancelled
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Update Package
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-boxes-stacked" style="font-size: 40px;"></i>
                                                <div class="mt-2">
                                                    No packages assigned yet.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                    <option value="Active">
                                        Active
                                    </option>
                                    <option value="Expired">
                                        Expired
                                    </option>
                                    <option value="Cancelled">
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
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
