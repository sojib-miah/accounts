@extends('BackEnd.Layouts.layout')

@section('title', 'Supplier List')

@section('content')
    <div class="p-5">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <h4>Supplier List</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('supplier.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <select name="company_id" class="form-select select2 form-control">
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Customer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
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
                            <th>SL</th>
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
                                <td>
                                    {{ $supplier->company->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $supplier->customerCompany->name ?? '-' }}
                                </td>
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
                                                    <button type="button" class="btn w-100 btn-warning btn-sm editSupplier"
                                                        data-id="{{ $supplier->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endcan
                                            </li>
                                            <li>
                                                @can('supplier-delete')
                                                    <button class="btn btn-danger w-100 btn-sm deleteSupplier"
                                                        data-id="{{ $supplier->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
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
                <div class="mt-3">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('BackEnd.Supplier.create')
    @include('BackEnd.Supplier.edit')
@endsection

@push('scripts')
    <script>
        // EDIT LOAD
        $(document).on('click', '.editSupplier', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '/admin/supplier/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#edit_supplier_id').val(data.id);
                    $('#edit_customer_company_id').val(data.customer_company_id);
                    $('#edit_name').val(data.name);
                    $('#edit_designation').val(data.designation);
                    $('#edit_phone').val(data.phone);
                    $('#edit_email').val(data.email);
                    $('#edit_status').val(data.status);
                    $('#edit_address').val(data.address);
                    $('#editSupplierModal').modal('show');
                }
            });
        });
        // UPDATE SUPPLIER
        $('#editSupplierForm').submit(function(e) {
            e.preventDefault();
            let id = $('#edit_supplier_id').val();
            $.ajax({
                url: '/admin/supplier/' + id,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#editSupplierModal').modal('hide');
                    Swal.fire({
                        title: 'Updated',
                        text: res.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Update failed',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        // DELETE SUPPLIER
        $(document).on('click', '.deleteSupplier', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Delete Supplier?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/supplier/' + id,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            Swal.fire({
                                title: 'Deleted',
                                text: res.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Cannot Delete',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
                }
            });
        });
        // SELECT2 MODAL FIX
        $('#createSupplierModal').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: '#createSupplierModal'
            });
        });
        $('#editSupplierModal').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: '#editSupplierModal'
            });
        });
    </script>
@endpush
