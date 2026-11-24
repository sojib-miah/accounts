@extends('BackEnd.Layouts.layout')
@section('title', 'admin login as user')
@section('content')
    <div class="p-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fa fa-user-secret me-2"></i>
                            Login As User
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            You will be logged out from your current account
                            and logged in as the selected user.
                        </div>
                        <form action="{{ route('admin.login-as-user.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    User ID <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="user_id"
                                    class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id') }}"
                                    placeholder="Enter User ID" min="1" required>

                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Enter Username" required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100"
                                onclick="return confirm('Are you sure you want to login as this user?')">
                                <i class="fa fa-sign-in-alt me-1"></i>
                                Login As User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
