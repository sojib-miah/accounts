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
                                <td>
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm" title="Restore User">
                                            <i class="fa fa-sync"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('users.forceDelete', $user->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Permanently delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Permanent Delete User">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
