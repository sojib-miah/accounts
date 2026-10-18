@extends('BackEnd.Layouts.layout')

@section('title', 'Product Category')

@section('content')
    <div class="p-5">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <h4><i class="fa-solid fa-list-ul me-2"></i> Product Category</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('product-category.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <div style="width: 200px;">
                            <select name="company_id" class="form-select select2 w-100">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search Product Category...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('product-category.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset
                            </a>
                        @endif
                    </form>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa fa-plus me-2"></i>
                        Add Category
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->company->name ?? '-' }}</td>
                                <td>{{ $category->name ?? '-' }}</td>
                                <td>
                                    @if ($category->status == 'Active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li class="mb-2">
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $category->id }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('product-category.destroy', $category) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('product-category.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5><i class="fa fa-plus me-2"></i> Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name', 'add') is-invalid @enderror"
                                placeholder="Enter Category Name..." required>
                            @error('name', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select select2 @error('status', 'add') is-invalid @enderror"
                                required>
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Inactive" {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('status', 'add')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    @foreach ($categories as $category)
        <div class="modal fade" id="editModal{{ $category->id }}" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('product-category.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5><i class="fa fa-edit me-2"></i> Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_category_id') == $category->id && $errors->getBag('edit')->any() ? old('name', $category->name) : $category->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror"
                                    placeholder="Enter Category Name..." required>
                                @error('name', 'edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2  @error('status', 'edit') is-invalid @enderror" required>
                                    <option value="Active"
                                        {{ old('status', $category->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $category->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('createModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_category_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categoryId = @json(session('edit_category_id'));
                const modal = document.getElementById('editModal' + categoryId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endsection
