@extends('BackEnd.Layouts.layout')

@section('title', 'Manage Supplier List')

@section('content')
    <div class="p-5">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <h4><i class="fa-solid fa-list-ul me-2"></i> Supplier List</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('supplier.index') }}" method="GET"
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
                                placeholder="Search Customer...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                    @can('supplier-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createSupplierModal">
                            <i class="fa fa-plus me-2"></i>
                            Add Supplier
                        </button>
                    @endcan
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Supplier ID</th>
                            <th>Company</th>
                            <th>Supplier Company</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Phone</th>
                            <th>E-mail</th>
                            <th>Address</th>
                            <th>Created By</th>
                            <th>Create Date</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration ?? '-' }}</td>
                                <td>{{ $supplier->party_id ?? '-' }}</td>
                                <td>{{ $supplier->company->name ?? '-' }}</td>
                                <td>{{ $supplier->customerCompany->name ?? '-' }}</td>
                                <td>{{ $supplier->name ?? '-' }}</td>
                                <td>{{ $supplier->designation ?? '-' }}</td>
                                <td>{{ $supplier->phone ?? '-' }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>{{ $supplier->address ?? '-' }}</td>
                                <td>{{ $supplier->creator->name ?? '-' }}</td>
                                <td>{{ date('d-m-Y', strtotime($supplier->created_at)) ?? '-' }}</td>
                                <td>{{ $supplier->status ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li class="mb-2">
                                                @can('supplier-edit')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('supplier-delete')
                                                    <form action="{{ route('supplier.destroy', $supplier->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fa fa-trash me-1"></i> Delete
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
                                <td colspan="13" class="text-center">No Supplier Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('BackEnd.Supplier.create')
    @include('BackEnd.Supplier.edit')
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('createSupplierModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_supplier_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const supplierId = @json(session('edit_supplier_id'));
                const modal = document.getElementById('editSupplierModal' + supplierId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
