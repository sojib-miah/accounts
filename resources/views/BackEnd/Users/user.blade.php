@extends('BackEnd.Layouts.layout')

@section('title', 'User Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="row g-6 mb-6">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Session</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $users->count() }}</h4>
                                    </div>
                                    <small class="mb-0">Total Users</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="icon-base ti tabler-users icon-26px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span class="text-heading">Active Users</span>
                                    <div class="d-flex align-items-center my-1">
                                        <h4 class="mb-0 me-2">{{ $users->where('status', 1)->count() }}</h4>
                                    </div>
                                    <small class="mb-0">Last week analytics</small>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="icon-base ti tabler-user-check icon-26px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-header border-bottom" style="padding: 8px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">All Users</h4>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('users.index') }}" method="GET"
                                class="d-flex justify-content-center align-items-center gap-2">

                                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search Users">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                    Search
                                </button>

                                @if (request('search'))
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        Reset
                                    </a>
                                @endif

                            </form>
                            @can('user-create')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fa fa-plus me-2"></i> Add User
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                        <div class="col-md-4 user_role"></div>
                        <div class="col-md-4 user_plan"></div>
                        <div class="col-md-4 user_status"></div>
                    </div>
                </div>
                <div class="card-datatable">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Company Name</th>
                                <th>User Name</th>
                                <th>Phone No</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Package</th>
                                <th>Created</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->company->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-label-primary">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($user->companyPackage)
                                            <span class="badge bg-primary">
                                                {{ $user->companyPackage->package->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                No Package
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        @can('user-edit')
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editUser{{ $user->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('user-delete')
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete User?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="py-5">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- add modal  --}}
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal">
                    </button>
                    <div class="text-center mb-4">
                        <h3>Add New User</h3>
                    </div>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required
                                    value="{{ old('email') }}">
                                @error('email', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select select2" required>
                                    <option value="">
                                        Select Role
                                    </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label>Package</label>
                                <select name="package_id" class="form-select select2" required>
                                    <option value="">Select Package</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">
                                            {{ $package->name }}
                                            ({{ number_format($package->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary me-2">
                                Create User
                            </button>
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal  --}}
    @foreach ($users as $user)
        <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5>Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}"
                                        class="form-control">
                                    @error('name', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label>Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="form-control">
                                    @error('email', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label>Role</label>
                                    <select name="role" class="form-select select2">
                                        <option value="">
                                            Select Role
                                        </option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label>Package</label>
                                    <select name="package_id" class="form-select select2" required>
                                        <option value="">Select Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}"
                                                {{ optional($user->companyPackage)->package_id == $package->id ? 'selected' : '' }}>
                                                {{ $package->name }}
                                                ({{ number_format($package->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('package_id', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
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
    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addUserModal')).show();
            });
        </script>
    @endif
@endpush
