@extends('BackEnd.Layouts.layout')

@section('title', 'Add Supplier')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Add Supplier</h4>
                <div>
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-left-long me-2"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('supplier.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name', $supplier->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control"
                                value="{{ old('designation', $supplier->designation) }}">
                            @error('designation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control" required
                                value="{{ old('company_name', $supplier->company_name) }}">
                            @error('company_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $supplier->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $supplier->email) }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="Active" {{ $supplier->status == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="Inactive" {{ $supplier->status == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
