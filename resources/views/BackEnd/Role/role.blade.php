@extends('BackEnd.Layouts.layout')

@section('title', 'Role Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <h4 class="mb-1">Roles List</h4>
            <p class="mb-6">
                A role provided access to predefined menus and features so that depending on
                assigned role an administrator can have access to what user needs.
            </p>
            <div class="row g-6">
                @foreach ($roles as $role)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-normal mb-0 text-body">
                                        Total {{ $role->users->count() }} users
                                    </h6>

                                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                        @foreach ($role->users->take(4) as $user)
                                            <li class="avatar pull-up">
                                                <img class="rounded-circle" src="{{ asset('assets/img/avatars/1.png') }}"
                                                    alt="Avatar">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-between align-items-end">
                                    <div class="role-heading">
                                        <h5 class="mb-1">{{ $role->name }}</h5>
                                        @can('role-edit')
                                            <a href="javascript:;" data-bs-toggle="modal"
                                                data-bs-target="#editRoleModal{{ $role->id }}">
                                                <span>Edit Role</span>
                                            </a>
                                        @endcan
                                    </div>
                                    @can('role-delete')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete Role?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card h-100">
                        <div class="row h-100">

                            <div class="col-sm-5">
                                <div class="d-flex align-items-end h-100 justify-content-center">
                                    <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid"
                                        width="83">
                                </div>
                            </div>

                            <div class="col-sm-7">
                                <div class="card-body text-sm-end text-center ps-sm-0">
                                    @can('role-create')
                                        <button data-bs-toggle="modal" data-bs-target="#addRoleModal"
                                            class="btn btn-sm btn-primary mb-4">
                                            Add New Role
                                        </button>
                                    @endcan
                                    <p class="mb-0">
                                        Add new role if it doesn't exist.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-2">
                            <div>
                                <h4 class="mb-1">Total users with their roles</h4>
                                <p class="mb-0">
                                    Find all of your company’s administrator accounts and their associate roles.
                                </p>
                            </div>
                            <form action="{{ route('roles.index') }}" method="GET"
                                class="d-flex justify-content-center align-items-center gap-2">

                                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search User">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search me-1"></i>
                                    Search
                                </button>

                                @if (request('search'))
                                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                        Reset
                                    </a>
                                @endif

                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $i => $user)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge bg-label-primary">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Role Modal --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                    <div class="text-center mb-4">
                        <h4>Add New Role</h4>
                        <p class="text-muted">Set role permissions</p>
                    </div>
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">
                                Role Name
                            </label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <h5 class="mb-3">Role Permissions</h5>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAllPermissions">
                                <label class="form-check-label fw-bold" for="checkAllPermissions">
                                    Select All Permissions
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($groupPermissions as $module => $modulePermissions)
                                <div class="col-md-3 mb-4">
                                    <div class="card border shadow-sm">
                                        <div class="card-header" style="padding: 5px !impotant">
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox" type="checkbox"
                                                    id="module_{{ Str::slug($module) }}">
                                                <label class="form-check-label fw-bold"
                                                    for="module_{{ Str::slug($module) }}">
                                                    {{ $module }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($modulePermissions as $permission)
                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input permission-checkbox module-{{ Str::slug($module) }}"
                                                        type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission{{ $permission->id }}">
                                                    <label class="form-check-label"
                                                        for="permission{{ $permission->id }}">
                                                        {{ ucfirst(last(explode('-', $permission->name))) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                Save Role
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

    {{-- Edit Role Modal --}}
    @foreach ($roles as $role)
        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h4>Edit Role</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label>Role Name</label>
                                <input type="text" name="name" value="{{ $role->name }}" class="form-control">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <h5 class="mb-3">Role Permissions</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input edit-check-all" type="checkbox"
                                        id="edit_check_all_{{ $role->id }}">
                                    <label class="form-check-label fw-bold" for="edit_check_all_{{ $role->id }}">
                                        Select All Permissions
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($groupPermissions as $module => $modulePermissions)
                                    <div class="col-md-3 mb-4">
                                        <div class="card border shadow-sm">
                                            <div class="card-header" style="padding: 5px !impotant">
                                                <div class="form-check">
                                                    <input class="form-check-input edit-module-checkbox" type="checkbox"
                                                        id="edit_module_{{ $role->id }}_{{ \Illuminate\Support\Str::slug($module) }}"
                                                        data-role="{{ $role->id }}"
                                                        data-module="{{ \Illuminate\Support\Str::slug($module) }}">
                                                    <label class="form-check-label fw-bold">
                                                        {{ $module }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($modulePermissions as $permission)
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox"
                                                            class="form-check-input
                                                            edit-permission-checkbox
                                                            edit-module-{{ $role->id }}-{{ \Illuminate\Support\Str::slug($module) }}"
                                                            name="permissions[]" value="{{ $permission->name }}"
                                                            id="edit_permission_{{ $role->id }}_{{ $permission->id }}"
                                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>

                                                        <label class="form-check-label"
                                                            for="edit_permission_{{ $role->id }}_{{ $permission->id }}">
                                                            {{ ucfirst(last(explode('-', $permission->name))) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('.modal').on('shown.bs.modal', function() {
                let modal = $(this);
                modal.find('.edit-module-checkbox').each(function() {
                    let role = $(this).data('role');
                    let module = $(this).data('module');
                    let total = modal.find('.edit-module-' + role + '-' + module).length;
                    let checked = modal.find('.edit-module-' + role + '-' + module + ':checked')
                        .length;
                    $(this).prop('checked', total == checked);
                });
                let totalPermission = modal.find('.edit-permission-checkbox').length;
                let totalChecked = modal.find('.edit-permission-checkbox:checked').length;
                modal.find('.edit-check-all').prop(
                    'checked',
                    totalPermission == totalChecked
                );
            });
            $('.edit-check-all').change(function() {
                let modal = $(this).closest('.modal');
                let checked = $(this).prop('checked');
                modal.find('.edit-permission-checkbox').prop('checked', checked);
                modal.find('.edit-module-checkbox').prop('checked', checked);
            });

            $('.edit-module-checkbox').change(function() {
                let modal = $(this).closest('.modal');
                let role = $(this).data('role');
                let module = $(this).data('module');
                modal.find('.edit-module-' + role + '-' + module)
                    .prop('checked', $(this).prop('checked'));
            });
            $('.edit-permission-checkbox').change(function() {
                let modal = $(this).closest('.modal');
                modal.find('.edit-module-checkbox').each(function() {
                    let role = $(this).data('role');
                    let module = $(this).data('module');
                    let total = modal.find('.edit-module-' + role + '-' + module).length;
                    let checked = modal.find('.edit-module-' + role + '-' + module + ':checked')
                        .length;
                    $(this).prop('checked', total == checked);
                });
                let total = modal.find('.edit-permission-checkbox').length;
                let checked = modal.find('.edit-permission-checkbox:checked').length;
                modal.find('.edit-check-all').prop('checked', total == checked);
            });
            $('#checkAllPermissions').on('change', function() {
                let checked = $(this).prop('checked');
                $('.permission-checkbox').prop('checked', checked);
                $('.module-checkbox').prop('checked', checked);
            });
            $('.module-checkbox').on('change', function() {
                let module = $(this).attr('id').replace('module_', '');
                $('.module-' + module).prop(
                    'checked',
                    $(this).prop('checked')
                );
                updateSelectAll();
            });
            $('.permission-checkbox').on('change', function() {
                let classes = $(this).attr('class').split(/\s+/);
                let moduleClass = '';
                $.each(classes, function(index, cls) {
                    if (cls.startsWith('module-')) {
                        moduleClass = cls;
                    }
                });
                if (moduleClass != '') {
                    let total = $('.' + moduleClass).length;
                    let checked = $('.' + moduleClass + ':checked').length;
                    let module = moduleClass.replace('module-', '');
                    $('#module_' + module).prop(
                        'checked',
                        total == checked
                    );
                }
                updateSelectAll();
            });

            function updateSelectAll() {
                let total = $('.permission-checkbox').length;
                let checked = $('.permission-checkbox:checked').length;
                $('#checkAllPermissions').prop(
                    'checked',
                    total == checked
                );
            }
        });
    </script>
@endpush
