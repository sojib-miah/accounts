@extends('BackEnd.Layouts.layout')

@section('title', 'Company Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fa-solid fa-list-ul me-2"></i> Company List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('company.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Company">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search'))
                            <a href="{{ route('company.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                    @can('company-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                            <i class="fa fa-plus me-2"></i> Add Company
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Hologram</th>
                                <th>Seal</th>
                                <th>Signature</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>
                                        @if ($company->logo)
                                            <img src="{{ asset($company->logo) }}" class="border rounded" width="60"
                                                height="60">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if ($company->hologram)
                                            <img src="{{ asset($company->hologram) }}" class="border rounded" width="60"
                                                height="60">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if ($company->seal)
                                            <img src="{{ asset($company->seal) }}" class="border rounded" width="60"
                                                height="60">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if ($company->signature)
                                            <img src="{{ asset($company->signature) }}" class="border rounded"
                                                width="80" height="60">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li class="mb-2">
                                                    @can('company-edit')
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editCompanyModal{{ $company->id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('company-delete')
                                                        <form action="{{ route('company.destroy', $company->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger" type="submit"
                                                                onclick="return confirm('Delete Company?')">
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
                                    <td colspan="7" class="text-center">No data Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addCompanyModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5><i class="fa fa-plus me-2"></i> Add Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" placeholder="Company Name..."
                                class="form-control @error('name', 'add') is-invalid @enderror" value="{{ old('name') }}"
                                required>
                            @error('name', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo"
                                class="form-control @error('logo', 'add') is-invalid @enderror">
                            @error('logo', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Hologram</label>
                            <input type="file" name="hologram"
                                class="form-control @error('hologram', 'add') is-invalid @enderror">
                            @error('hologram', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Seal</label>
                            <input type="file" name="seal"
                                class="form-control @error('seal', 'add') is-invalid @enderror">
                            @error('seal', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Signature</label>
                            <input type="file" name="signature"
                                class="form-control @error('signature', 'add') is-invalid @enderror">
                            @error('signature', 'add')
                                <small class="invalid-feedback">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Company Modal -->
    @foreach ($companies as $company)
        <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('company.update', $company->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-edit me-2"></i> Edit Company
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        value="{{ session('edit_company_id') == $company->id && $errors->getBag('edit')->any() ? old('name', $company->name) : $company->name }}"
                                        class="form-control @error('name', 'edit') is-invalid @enderror"
                                        placeholder="Company Name..." required>
                                    @error('name', 'edit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @foreach (['logo' => 'Logo', 'hologram' => 'Hologram', 'seal' => 'Seal', 'signature' => 'Signature'] as $field => $label)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ $label }}</label>
                                        <input type="file" name="{{ $field }}" class="form-control"
                                            accept=".jpg,.jpeg,.png,.webp">
                                        @error($field, 'edit')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @if ($company->$field)
                                            <img src="{{ asset($company->$field) }}" class="mt-2 border rounded"
                                                width="100" height="100" alt="{{ $label }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-close me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save me-1"></i> Update Company
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
                const modal = document.getElementById('addCompanyModal');

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_company_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const companyId = @json(session('edit_company_id'));
                const modal = document.getElementById('editCompanyModal' + companyId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
