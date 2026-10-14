@extends('BackEnd.Layouts.layout')

@section('title', 'Supplier companies')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0">Supplier Company</h4>
                    <small class="text-muted">Manage Supplier companies</small>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <form action="{{ route('supplier-company.index') }}" method="GET" class="d-flex gap-2">
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
                                placeholder="Search supplier...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('supplier-company.index') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addCustomerCompanyModal">
                        <i class="fa fa-plus me-1"></i>
                        Add Supplier Company
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
                                    <th width="60">SN</th>
                                    <th>Company</th>
                                    <th>Supplier Company</th>
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
                                        <td>{{ $company->email ?: '-' }}</td>
                                        <td>{{ $company->phone ?: '-' }}</td>
                                        <td>{{ $company->address ?: '-' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $company->status ?: '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editCustomerCompanyModal{{ $company->id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('supplier-company.destroy', $company->id) }}"
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
                                            No Supplier Company Found
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
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addCustomerCompanyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('supplier-company.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-building me-2"></i>
                            Add Supplier Company
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Company Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" value="{{ $errors->add->any() ? old('name') : '' }}"
                                    class="form-control @error('name', 'add') is-invalid @enderror"
                                    placeholder="Enter company name" required>
                                @error('name', 'add')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ $errors->add->any() ? old('email') : '' }}"
                                    class="form-control @error('email', 'add') is-invalid @enderror"
                                    placeholder="company@example.com">
                                @error('email', 'add')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ $errors->add->any() ? old('phone') : '' }}"
                                    class="form-control @error('phone', 'add') is-invalid @enderror"
                                    placeholder="Enter phone number">
                                @error('phone', 'add')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" rows="3" class="form-control @error('address', 'add') is-invalid @enderror"
                                    placeholder="Enter company address">{{ $errors->add->any() ? old('address') : '' }}</textarea>
                                @error('address', 'add')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fa-solid fa-xmark me-1"></i> Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit modal  --}}
    @foreach ($customerCompanies as $company)
        <div class="modal fade" id="editCustomerCompanyModal{{ $company->id }}" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('supplier-company.update', $company->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-edit me-2"></i>
                                Edit Supplier Company
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Company Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        value="{{ $errors->edit->any() && old('_edit_id') == $company->id ? old('name') : $company->name }}"
                                        class="form-control {{ $errors->edit->has('name') && old('_edit_id') == $company->id ? 'is-invalid' : '' }}"
                                        required>
                                    @if ($errors->edit->has('name') && old('_edit_id') == $company->id)
                                        <div class="invalid-feedback">
                                            {{ $errors->edit->first('name') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        value="{{ $errors->edit->any() && old('_edit_id') == $company->id ? old('email') : $company->email }}"
                                        class="form-control {{ $errors->edit->has('email') && old('_edit_id') == $company->id ? 'is-invalid' : '' }}">
                                    @if ($errors->edit->has('email') && old('_edit_id') == $company->id)
                                        <div class="invalid-feedback">
                                            {{ $errors->edit->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone"
                                        value="{{ $errors->edit->any() && old('_edit_id') == $company->id ? old('phone') : $company->phone }}"
                                        class="form-control {{ $errors->edit->has('phone') && old('_edit_id') == $company->id ? 'is-invalid' : '' }}">
                                    @if ($errors->edit->has('phone') && old('_edit_id') == $company->id)
                                        <div class="invalid-feedback">
                                            {{ $errors->edit->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" rows="3"
                                        class="form-control {{ $errors->edit->has('address') && old('_edit_id') == $company->id ? 'is-invalid' : '' }}">{{ $errors->edit->any() && old('_edit_id') == $company->id ? old('address') : $company->address }}</textarea>
                                    @if ($errors->edit->has('address') && old('_edit_id') == $company->id)
                                        <div class="invalid-feedback">
                                            {{ $errors->edit->first('address') }}
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" name="_edit_id" value="{{ $company->id }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                    class="fa-solid fa-xmark me-1"></i> Close</button>
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


@push('scripts')

    @if ($errors->add->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(
                    document.getElementById('addCustomerCompanyModal')
                ).show();
            });
        </script>
    @elseif ($errors->edit->any() && old('_edit_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let modal = document.getElementById(
                    'editCustomerCompanyModal{{ old('_edit_id') }}'
                );

                if (modal) {
                    new bootstrap.Modal(modal).show();
                }
            });
        </script>
    @endif

@endpush
