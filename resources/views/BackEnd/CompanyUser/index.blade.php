@extends('BackEnd.Layouts.layout')

@section('title', 'Company User Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fa-solid fa-list-ul me-2"></i> Company User List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('user.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <div style="width: 200px;">
                            <select name="company_id" class="form-select select2 w-100">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search Company User...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                    @can('company-user-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addCompanyUserModal">
                            <i class="fa fa-plus me-2"></i> Add Company User
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="50">SN</th>
                                <th>Company Name</th>
                                <th>Branch</th>
                                <th>User Name</th>
                                <th>Phone No</th>
                                <th>E-mail</th>
                                <th>Created By</th>
                                <th width="150" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->company->name ?? '-' }}</td>
                                    <td>{{ $user->branch->name ?? '-' }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->creator->name ?? 'Self Created' }}</td>
                                    <td class="text-center">
                                        @if (!$user->hasRole('Super-Admin'))
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @can('company-user-edit')
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editCompanyUserModal{{ $user->id }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </button>
                                                        @endcan
                                                    </li>
                                                    <li>
                                                        @can('company-user-delete')
                                                            <form action="{{ route('user.destroy', $user->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    type="submit"
                                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No User Found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Company User Modal -->
    <div class="modal fade" id="addCompanyUserModal" tabindex="-1" aria-labelledby="addCompanyUserModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyUserModalLabel">
                            <i class="fa fa-plus me-2"></i> Add Company User
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Company -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Company <span class="text-danger">*</span>
                                </label>
                                <select name="company_id"
                                    class="form-select select2 @error('company_id', 'add') is-invalid @enderror" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Branch -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Branch <span class="text-danger">*</span>
                                </label>
                                <select name="branch_id"
                                    class="form-select select2 @error('branch_id', 'add') is-invalid @enderror" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name', 'add') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Enter Full Name..." required>
                                @error('name', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email', 'add') is-invalid @enderror"
                                    placeholder="Enter Email..." required>
                                @error('email', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Phone <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone', 'add') is-invalid @enderror"
                                    placeholder="Enter Phone Number..." value="{{ old('phone') }}" required>
                                @error('phone', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password"
                                    class="form-control @error('password', 'add') is-invalid @enderror"
                                    placeholder="Enter Password..." required>
                                @error('password', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Company User Modal -->
    @foreach ($users as $user)
        <div class="modal fade" id="editCompanyUserModal{{ $user->id }}" tabindex="-1"
            aria-labelledby="editCompanyUserModalLabel" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-edit me-2"></i> Edit Company User
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Company -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Company <span class="text-danger">*</span>
                                    </label>
                                    <select name="company_id"
                                        class="form-select select2 @error('company_id', 'edit') is-invalid @enderror">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ (string) old('company_id', $user->company_id) === (string) $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Branch -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Branch <span class="text-danger">*</span>
                                    </label>
                                    <select name="branch_id"
                                        class="form-select select2 @error('branch_id', 'edit') is-invalid @enderror">
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ (string) old('branch_id', $user->branch_id) === (string) $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        value="{{ session('edit_user_id') == $user->id && $errors->getBag('edit')->any() ? old('name', $user->name) : $user->name }}"
                                        class="form-control @error('name', 'edit') is-invalid @enderror"
                                        placeholder="Enter Full Name..." required>
                                    @error('name', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email"
                                        value="{{ session('edit_user_id') == $user->id && $errors->getBag('edit')->any() ? old('email', $user->email) : $user->email }}"
                                        class="form-control @error('email', 'edit') is-invalid @enderror"
                                        placeholder="Enter Email..." required>
                                    @error('email', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Phone <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="phone"
                                        value="{{ session('edit_user_id') == $user->id && $errors->getBag('edit')->any() ? old('phone', $user->phone) : $user->phone }}"
                                        class="form-control @error('phone', 'edit') is-invalid @enderror"
                                        placeholder="Enter Phone Number..." required>
                                    @error('phone', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Password -->
                                {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" id="edit_password" class="form-control" required>
                            </div>
                            @error('password', 'edit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror --}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save me-1"></i>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addCompanyUserModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_user_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const userId = @json(session('edit_user_id'));
                const modal = document.getElementById('editCompanyUserModal' + userId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
