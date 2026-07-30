@extends('BackEnd.Layouts.layout')

@section('title', 'Add Product Category')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Add Product Category</h4>
                <div>
                    <a href="{{ route('product-category.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('product-category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <button class="btn btn-success">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
