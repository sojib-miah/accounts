@extends('BackEnd.Layouts.layout')

@section('title', 'Profile Management')

@section('content')
    <div class="container-p-y p-5">
        <div class="row">
            <!-- Left Side -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if ($user->image)
                            <img src="{{ asset('uploads/users/' . $user->image) }}" class="rounded-circle mb-3" width="120">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $user->name }}" class="rounded-circle mb-3"
                                width="120">
                        @endif
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">
                            {{ $user->email }}
                        </p>
                        <h6>
                            Role :
                            @foreach ($user->roles as $role)
                                <span class="badge bg-primary">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </h6>
                        <hr>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#passwordModal">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>
            <!-- Right Side -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label>Role</label>
                                <input type="text" class="form-control"
                                    value="{{ $user->roles->pluck('name')->implode(', ') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Profile Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <button class="btn btn-primary">
                                Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5>
                            Change Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>
                                Current Password
                            </label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>
                                New Password
                            </label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
