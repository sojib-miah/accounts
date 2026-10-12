@extends('BackEnd.Layouts.layout')

@section('title', 'Customer List')

@section('content')
    <div class="p-5">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Customer Company</h4>
                <small class="text-muted">Manage customer companies</small>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <form action="{{ route('customer-company.index') }}" method="GET" class="d-flex gap-2">
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
                        <a href="{{ route('customer-company.index') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh me-1"></i>
                            Reset
                        </a>
                    @endif
                </form>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fa fa-plus me-1"></i>
                    Add Customer Company
                </button>
            </div>
        </div>
        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="customerCompanyTable">
                        <thead>
                            <tr>
                                <th width="60">SL</th>
                                <th>Company</th>
                                <th>Customer Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th width="100">Status</th>
                                <th width="130">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerCompanies as $key => $company)
                                <tr id="row-{{ $company->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $company->company->name ?? '-' }}</td>
                                    <td>{{ $company->name ?? '-' }}</td>
                                    <td>{{ $company->email ?? '-' }}</td>
                                    <td>{{ $company->phone ?? '-' }}</td>
                                    <td>{{ $company->address ?? '-' }}</td>
                                    <td>
                                        @if ($company->status === 'Customer')
                                            <span class="badge bg-success">
                                                Customer
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Supplier
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
                                                        data-bs-target="#editCustomerModal{{ $company->id }}">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('customer-company.destroy', $company->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this company?')">
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
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No Customer Company Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $customerCompanies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('customer-company.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">
                            Add Customer Company
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name', 'add') is-invalid @enderror">
                            @error('name', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email', 'add') is-invalid @enderror">
                            @error('email', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone', 'add') is-invalid @enderror">
                            @error('phone', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" rows="3" class="form-control @error('address', 'add') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address', 'add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit modal  --}}
    <div class="modal fade" id="editCustomerModal{{ $company->id }}" tabindex="-1"
        aria-labelledby="editCustomerModalLabel{{ $company->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('customer-company.update', $company->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_edit_company_id" value="{{ $company->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel{{ $company->id }}">
                            Edit Customer Company
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name *</label>

                            <input type="text" name="name"
                                value="{{ old('_edit_company_id') == $company->id ? old('name') : $company->name }}"
                                class="form-control @error('name', 'edit') is-invalid @enderror" required>

                            @error('name', 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input type="email" name="email"
                                value="{{ old('_edit_company_id') == $company->id ? old('email') : $company->email }}"
                                class="form-control @error('email', 'edit') is-invalid @enderror">

                            @error('email', 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>

                            <input type="text" name="phone"
                                value="{{ old('_edit_company_id') == $company->id ? old('phone') : $company->phone }}"
                                class="form-control @error('phone', 'edit') is-invalid @enderror">

                            @error('phone', 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>

                            <textarea name="address" rows="3" class="form-control @error('address', 'edit') is-invalid @enderror">{{ old('_edit_company_id') == $company->id ? old('address') : $company->address }}</textarea>

                            @error('address', 'edit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addCustomerModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any())
        @foreach ($companies as $company)
            @if (old('_edit_company_id') == $company->id)
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalElement = document.getElementById(
                            'editCustomerModal{{ $company->id }}'
                        );

                        if (modalElement) {
                            bootstrap.Modal.getOrCreateInstance(modalElement).show();
                        }
                    });
                </script>
            @endif
        @endforeach
    @endif
@endpush
