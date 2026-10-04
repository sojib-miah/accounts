@extends('BackEnd.Layouts.layout')

@section('title', 'Expense List Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    Expense Description (Item Creation)
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('account-head.index') }}" method="GET"
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
                                placeholder="Search Expense List">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('account-head.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('expense-list-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountHeadModal">
                            <i class="fa fa-plus me-1"></i>
                            Add Expense
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
                                    <th>Category</th>
                                    <th>Description</th>
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
                                        <td>{{ $head->company->name ?? '-' }}</td>
                                        <td>{{ $head->category->name ?? '-' }}</td>
                                        <td>{{ $head->name ?? '-' }}</td>
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
                                            {{ date('d-m-Y', strtotime($head->created_at)) ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @can('expense-list-edit')
                                                            <button class="dropdown-item editBtn" data-id="{{ $head->id }}"
                                                                data-category="{{ $head->category_id }}"
                                                                data-name="{{ $head->name }}"
                                                                data-status="{{ $head->status }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </button>
                                                        @endcan
                                                    </li>
                                                    <li>
                                                        @can('expense-list-delete')
                                                            <form action="{{ route('account-head.destroy', $head->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger" type="submit"
                                                                    onclick="return confirm('Delete this Expense Description?')">
                                                                    <i class="fa fa-trash"></i> Delete
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
                                        <td colspan="10" class="text-center">
                                            No Expense Description Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $accountHeads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('BackEnd.AccountHead.create')
    @include('BackEnd.AccountHead.edit')
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                $('#edit_category_id').val(this.dataset.category).trigger('change');
                document.getElementById('edit_name').value = this.dataset.name;
                $('#edit_status').val(this.dataset.status).trigger('change');
                document.getElementById('editAccountHeadForm').action =
                    "{{ url('/admin/account-head') }}/" + this.dataset.id;
                new bootstrap.Modal(
                    document.getElementById('editAccountHeadModal')
                ).show();
            });
        });
    </script>

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addAccountHeadModal')).show();
            });
        </script>
    @endif

    @if ($errors->edit->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editAccountHeadModal')).show();
            });
        </script>
    @endif
@endpush
