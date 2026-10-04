@extends('BackEnd.Layouts.layout')

@section('title', 'Soft Delete Users List')

@section('content')
    <div class="py-5 px-5">
        <div class="card">
            <div class="card-header">
                <h4>Deleted Users</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Deleted At</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ optional($user->company)->name }}</td>
                                <td>{{ optional($user->branch)->name }}</td>
                                <td>{{ $user->deleted_at->format('d M Y h:i A') }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li>
                                                <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="dropdown-item" type="submit"
                                                        onclick="return confirm('Are you sure you want to restore this user?')"
                                                        title="Restore User">
                                                        <i class="fa fa-sync"></i> Restore
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('users.forceDelete', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger" type="submit"
                                                        title="Permanent Delete User"
                                                        onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                                        <i class="fa fa-trash"></i> Permanent Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No deleted users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
