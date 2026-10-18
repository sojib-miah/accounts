@extends('BackEnd.Layouts.layout')

@section('title', 'Payment Type')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="fa-solid fa-list-ul me-2"></i> Payment Type List
                </h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('payment-type.index') }}" method="GET"
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
                                placeholder="Search Payment Type">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('payment-type.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset
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
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Company Name</th>
                                <th>Payment Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentTypes as $paymentType)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $paymentType->company->name ?? '-' }}</td>
                                    <td>{{ $paymentType->name ?? '-' }}</td>
                                    <td>
                                        @if ($paymentType->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li class="mb-2">
                                                    @can('payment-type-edit')
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editModal{{ $paymentType->id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('payment-type-delete')
                                                        <form action="{{ route('payment-type.destroy', $paymentType->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this Payment Type?')">
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
                                    <td colspan="5" class="text-center">
                                        No Data Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $paymentTypes->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- add modal  --}}
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payment-type.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5><i class="fa fa-plus me-2"></i> Add Payment Type</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Payment Type <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name', 'add') is-invalid @enderror"
                                placeholder="Payment Type..." required>
                            @error('name', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select select2 @error('status', 'add') is-invalid @enderror"
                                required>
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="Inactive" {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status', 'add')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit modal  --}}
    @foreach ($paymentTypes as $paymentType)
        <div class="modal fade" id="editModal{{ $paymentType->id }}" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('payment-type.update', $paymentType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5><i class="fa fa-edit me-2"></i> Edit Payment Type</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Payment Type <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_payment_id') == $paymentType->id && $errors->getBag('edit')->any() ? old('name', $paymentType->name) : $paymentType->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror"
                                    placeholder="Payment Type..." required>
                                @error('name', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror" required>
                                    <option value="Active"
                                        {{ old('status', $paymentType->status) == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $paymentType->status) == 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
                                <i class="fa-solid fa-xmark me-1"></i> Close
                            </button>
                            <button class="btn btn-success">
                                <i class="fa fa-save me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_payment_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const paymentId = @json(session('edit_payment_id'));
                const modal = document.getElementById('editModal' + paymentId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
