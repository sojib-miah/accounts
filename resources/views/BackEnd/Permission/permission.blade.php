@extends('BackEnd.Layouts.layout')

@section('title', 'Permission Manegment')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Permissions List</h4>
                    <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('permissions.index') }}" method="GET"
                            class="d-flex justify-content-center align-items-center gap-2">

                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search Permission">

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i>
                                Search
                            </button>

                            @if (request('search'))
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                    Reset
                                </a>
                            @endif

                        </form>
                        @can('permission-create')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                <i class="fa fa-plus me-2"></i> Add Permission
                            </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-datatable table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Assigned Roles</th>
                                    <th>Created Date</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $permission->name }}</strong>
                                        </td>
                                        <td>
                                            @foreach ($permission->roles as $role)
                                                <span class="badge bg-label-primary me-1">
                                                    {{ $role->name ?? '-' }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $permission->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            @can('permission-edit')
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editPermissionModal{{ $permission->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('permission-delete')
                                                <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete Permission?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No Permission Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- ADD MODAL --}}
            <div class="modal fade" id="addPermissionModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-simple">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal">
                            </button>
                            <div class="text-center mb-4">
                                <h3>Add New Permission</h3>
                                <p class="text-body-secondary">
                                    Permissions you may use and assign to your users.
                                </p>
                            </div>
                            <form action="{{ route('permissions.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">
                                        Permission Name
                                    </label>
                                    <input type="text" name="name" class="form-control" placeholder="Permission Name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <div class="my-2">
                                        <label>
                                            <input type="checkbox" id="checkAll">
                                            Select All
                                        </label>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <label>
                                                <input type="checkbox" name="actions[]" value="list">
                                                List
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label>
                                                <input type="checkbox" name="actions[]" value="create">
                                                Create
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label>
                                                <input type="checkbox" name="actions[]" value="edit">
                                                Edit
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <label>
                                                <input type="checkbox" name="actions[]" value="delete">
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary me-2">
                                        Create Permission
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
            {{-- EDIT MODALS --}}
            @foreach ($permissions as $permission)
                <div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-simple">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal">
                                </button>
                                <div class="text-center mb-4">
                                    <h3>Edit Permission</h3>
                                    <p class="text-body-secondary">
                                        Edit permission as per your requirements.
                                    </p>
                                </div>
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        Warning
                                    </h6>
                                    <p class="mb-0">
                                        Changing permission names may affect system authorization.
                                    </p>
                                </div>
                                <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="form-label">
                                            Permission Name
                                        </label>
                                        <input type="text" name="name" value="{{ $permission->name }}"
                                            class="form-control">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Update Permission
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#checkAll').change(function() {

            $('input[name="actions[]"]')
                .prop('checked', $(this).prop('checked'));

        });
    </script>
@endpush
