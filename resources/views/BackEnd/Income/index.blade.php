@extends('BackEnd.Layouts.layout')

@section('title', 'Item List Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    Item List
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('income.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Income List">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('income.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('income-list-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountHeadModal">
                            <i class="fa fa-plus me-1"></i>
                            Add Item
                        </button>
                    @endcan
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="60">SL</th>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accountHeads as $head)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $head->category->name ?? '-' }}</td>
                                        <td>{{ $head->name }}</td>
                                        <td>
                                            @if ($head->type == 'Income')
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
                                            @if ($head->status == 'Active')
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
                                            {{ $head->creator->name ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $head->created_at->format('d M Y h:i A') }}
                                        </td>
                                        <td>
                                            @can('income-list-edit')
                                                <button class="btn btn-warning btn-sm editBtn" data-id="{{ $head->id }}"
                                                    data-category="{{ $head->category_id }}" data-name="{{ $head->name }}"
                                                    data-status="{{ $head->status }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('income-list-delete')
                                                <form action="{{ route('income.destroy', $head->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Delete this Income?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            No Income Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('BackEnd.Income.create')
    @include('BackEnd.Income.edit')
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                $('#edit_category_id').val(this.dataset.category).trigger('change');
                document.getElementById('edit_name').value = this.dataset.name;
                $('#edit_status').val(this.dataset.status).trigger('change');
                document.getElementById('editAccountHeadForm').action =
                    "{{ url('/admin/income') }}/" + this.dataset.id;
                new bootstrap.Modal(
                    document.getElementById('editAccountHeadModal')
                ).show();
            });
        });
    </script>
@endpush
