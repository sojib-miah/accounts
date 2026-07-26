@extends('BackEnd.Layouts.layout')

@section('title', 'Package')

@section('content')
    <div class="py-5 px-5">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h4 class="mb-0">
                            <i class="bx bx-package"></i>
                            Package List
                        </h4>
                    </div>
                    <div class="col-md-8">
                        <form method="GET">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search Package..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-search me-2"></i>
                                        Search
                                    </button>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#addPackageModal">
                                        <i class="fa fa-plus me-2"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Package</th>
                                <th>Price</th>
                                <th>User</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Income</th>
                                <th>Expense</th>
                                <th>Challan</th>
                                <th>Party</th>
                                <th>Account</th>
                                <th>Payment Type</th>
                                <th>Category</th>
                                <th>Item list</th>
                                <th>Sales Order</th>
                                <th>End Date</th>
                                <th>Storage</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $key=>$package)
                                <tr>
                                    <td>
                                        {{ $packages->firstItem() + $key }}
                                    </td>
                                    <td>
                                        <strong>
                                            {{ $package->name }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ number_format($package->price, 2) }}
                                    </td>
                                    <td>
                                        {{ $package->user_limit }}
                                    </td>
                                    <td>
                                        {{ $package->company_limit }}

                                    </td>
                                    <td>
                                        {{ $package->branch_limit }}
                                    </td>
                                    <td>
                                        {{ $package->income_limit }}
                                    </td>
                                    <td>
                                        {{ $package->expense_limit }}
                                    </td>
                                    <td>
                                        {{ $package->challan_limit }}
                                    </td>
                                    <td>
                                        {{ $package->party_limit }}
                                    </td>
                                    <td>{{ $package->account_limit }}</td>
                                    <td>{{ $package->payment_type_limit }}</td>
                                    <td>{{ $package->category_limit }}</td>
                                    <td>{{ $package->item_list_limit }}</td>
                                    <td>{{ $package->sales_order_limit }}</td>
                                    <td>{{ $package->end_date ?? '-' }}</td>
                                    <td>
                                        {{ $package->storage_limit }}
                                    </td>
                                    <td>
                                        @if ($package->is_active)
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li>
                                                    <a href="{{ route('package.show', $package->id) }}"
                                                        class="btn btn-info btn-sm w-100 mb-1">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="btn btn-warning btn-sm editBtn w-100 mb-1"
                                                        data-id="{{ $package->id }}" data-name="{{ $package->name }}"
                                                        data-price="{{ $package->price }}"
                                                        data-user="{{ $package->user_limit }}"
                                                        data-company="{{ $package->company_limit }}"
                                                        data-branch="{{ $package->branch_limit }}"
                                                        data-income="{{ $package->income_limit }}"
                                                        data-expense="{{ $package->expense_limit }}"
                                                        data-challan="{{ $package->challan_limit }}"
                                                        data-party="{{ $package->party_limit }}"
                                                        data-account="{{ $package->account_limit }}"
                                                        data-payment="{{ $package->payment_type_limit }}"
                                                        data-category="{{ $package->category_limit }}"
                                                        data-item="{{ $package->item_list_limit }}"
                                                        data-sales="{{ $package->sales_order_limit }}"
                                                        data-end_date="{{ $package->end_date?->format('Y-m-d') }}"
                                                        data-storage="{{ $package->storage_limit }}"
                                                        data-status="{{ $package->is_active }}"
                                                        data-remarks="{{ $package->remarks }}">
                                                        <i class="fa fa-edit"></i>

                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('package.destroy', $package->id) }}"
                                                        method="POST" class="deleteForm d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm w-100 mb-1">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="14" class="text-center py-5">
                                        <h5 class="text-muted">
                                            No Package Found
                                        </h5>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            @if ($packages->hasPages())
                <div class="card-footer">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
    {{-- Add Modal --}}
    @include('BackEnd.Package.create')

    {{-- Edit Modal --}}
    @include('BackEnd.Package.edit')

@endsection

@push('scripts')
    <script>
        $('.editBtn').click(function() {
            let id = $(this).data('id');
            $('#editForm').attr(
                'action',
                "{{ url('admin/package') }}/" + id
            );
            $('#edit_name').val($(this).data('name'));
            $('#edit_price').val($(this).data('price'));
            $('#edit_user_limit').val($(this).data('user'));
            $('#edit_company_limit').val($(this).data('company'));
            $('#edit_branch_limit').val($(this).data('branch'));
            $('#edit_income_limit').val($(this).data('income'));
            $('#edit_expense_limit').val($(this).data('expense'));
            $('#edit_challan_limit').val($(this).data('challan'));
            $('#edit_party_limit').val($(this).data('party'));
            $('#edit_account_limit').val($(this).data('account'));
            $('#edit_payment_type_limit').val($(this).data('payment'));
            $('#edit_category_limit').val($(this).data('category'));
            $('#edit_item_list_limit').val($(this).data('item'));
            $('#edit_sales_order_limit').val($(this).data('sales'));
            $('#edit_end_date').val($(this).data('end_date'));
            $('#edit_storage_limit').val($(this).data('storage'));
            $('#edit_status').val($(this).data('status'));
            $('#edit_remarks').val($(this).data('remarks'));
            $('#editPackageModal').modal('show');
        });

        $('.deleteForm').submit(function(e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Delete Package?',
                text: "You won't be able to recover it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addPackageModal')).show();
            });
        </script>
    @endif

    @if ($errors->edit->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editPackageModal')).show();
            });
        </script>
    @endif
@endpush
