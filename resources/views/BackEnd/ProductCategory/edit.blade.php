@extends('BackEnd.Layouts.layout')

@section('title', 'Edit Product Category')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Edit Product Category</h4>
                <div>
                    <a href="{{ route('product-category.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('product-category.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="Active" {{ $category->status == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="Inactive" {{ $category->status == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>
                    <button class="btn btn-primary">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
