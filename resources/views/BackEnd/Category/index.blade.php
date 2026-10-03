@extends('BackEnd.Layouts.layout')

@section('title', 'Category Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Expense Category List</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('category.index') }}" method="GET"
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
                                placeholder="Search Expense Category List">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('category.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif
                    </form>
                    @can('expense-category-list-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addCategoryModal">
                            <i class="fa fa-plus me-1"></i>
                            Add Category
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="60">SN</th>
                                    <th>Company Name</th>
                                    <th>Category Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->company->name ?? '-' }}</td>
                                        <td>{{ $category->name ?? '-' }}</td>
                                        <td>
                                            @if ($category->type == 'Income')
                                                <span class="badge bg-success">
                                                    Income
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    Expense
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->status == 'Active')
                                                <span class="badge bg-success">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $category->creator->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($category->created_at)) }}
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @can('expense-category-list-edit')
                                                            <button type="button" class="btn btn-warning w-100 btn-sm editBtn"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}"
                                                                data-status="{{ $category->status }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        @endcan
                                                    </li>
                                                    <li>
                                                        @can('expense-category-list-delete')
                                                            <form action="{{ route('category.destroy', $category->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger w-100 btn-sm"
                                                                    onclick="return confirm('Delete this category?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            No Category Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Modal --}}
    @include('BackEnd.Category.create')
    {{-- Edit Modal --}}
    @include('BackEnd.Category.edit')
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('edit_name').value = this.dataset.name;
                $('#edit_status').val(this.dataset.status).trigger('change');
                document.getElementById('editCategoryForm').action =
                    "{{ url('/admin/category') }}/" + this.dataset.id;
                new bootstrap.Modal(
                    document.getElementById('editCategoryModal')
                ).show();
            });
        });
    </script>

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
            });
        </script>
    @endif

    @if ($errors->edit->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
            });
        </script>
    @endif
@endpush
