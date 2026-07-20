@extends('BackEnd.Layouts.layout')

@section('title', 'Payment Type')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    Payment Type List
                </h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('payment-type.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Payment Type">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('payment-type.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('payment-type-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus me-2"></i>
                            Add Payment Type
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Payment Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentTypes as $paymentType)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $paymentType->name }}
                                    </td>
                                    <td>
                                        @if ($paymentType->status == 'Active')
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
                                        @can('payment-type-edit')
                                            <button class="btn btn-warning btn-sm editBtn" data-id="{{ $paymentType->id }}"
                                                data-name="{{ $paymentType->name }}" data-status="{{ $paymentType->status }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('payment-type-delete')
                                            <form action="{{ route('payment-type.destroy', $paymentType->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete this Payment Type?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        No Data Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- add modal  --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payment-type.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Payment Type</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Payment Type</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select select2">
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            Close
                        </button>
                        <button class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit modal  --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5>Edit Payment Type</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Payment Type</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-select select2">
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            Close
                        </button>
                        <button class="btn btn-success">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('edit_name').value = this.dataset.name;
                $('#edit_status').val(this.dataset.status).trigger('change');
                document.getElementById('editForm').action = '/admin/payment-type/update/' + this.dataset
                    .id;
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
    </script>

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('addModal')).show();
            });
        </script>
    @endif

    @if ($errors->edit->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        </script>
    @endif
@endpush
