@extends('BackEnd.Layouts.layout')

@section('title', 'Company User Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Company User List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('user.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Company User">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
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
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->company->name ?? '-' }}</td>
                                    <td>{{ $user->branch->name ?? '-' }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->creator->name ?? '-' }}</td>
                                    <td class="text-center">
                                        @if (!$user->hasRole('Super-Admin'))
                                            @can('company-user-edit')
                                                <button type="button" class="btn btn-sm btn-warning editBtn"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}" data-phone="{{ $user->phone }}"
                                                    data-company="{{ $user->company_id }}"
                                                    data-branch="{{ $user->branch_id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('company-user-delete')
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
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
            </div>
        </div>
    </div>

    <!-- Add Company User Modal -->
    <div class="modal fade" id="addCompanyUserModal" tabindex="-1" aria-labelledby="addCompanyUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyUserModalLabel">
                            Add Company User
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
                                <select name="company_id" class="form-select select2" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
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
                                <select name="branch_id" class="form-select select2" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
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
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    placeholder="Enter Full Name" required>
                                @error('name', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    placeholder="Enter Email" required>
                                @error('email', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Phone <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control"
                                    placeholder="Enter Phone Number" value="{{ old('phone') }}" required>
                                @error('phone', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="Enter Password"
                                    required>
                                @error('password', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
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
    <div class="modal fade" id="editCompanyUserModal" tabindex="-1" aria-labelledby="editCompanyUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCompanyUserModalLabel">
                            Edit Company User
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
                                <select name="company_id" id="edit_company_id" class="form-select select2" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
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
                                <select name="branch_id" id="edit_branch_id" class="form-select select2" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
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
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                                @error('name', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                                @error('email', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Phone <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" id="edit_phone" class="form-control" required>
                                @error('phone', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
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
                            Close
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
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_email').value = this.dataset.email;
                document.getElementById('edit_phone').value = this.dataset.phone;
                $('#edit_company_id').val(this.dataset.company).trigger('change');
                $('#edit_branch_id').val(this.dataset.branch).trigger('change');
                document.getElementById('editUserForm').action =
                    "{{ url('/admin/user') }}/" + this.dataset.id;

                let modal = new bootstrap.Modal(document.getElementById('editCompanyUserModal'));
                modal.show();

            });

        });
    </script>

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addCompanyUserModal')).show();
            });
        </script>
    @endif

    @if ($errors->edit->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editCompanyUserModal')).show();
            });
        </script>
    @endif
@endpush
