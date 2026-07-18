@extends('BackEnd.Layouts.layout')

@section('title', 'Company Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Company List</h4>
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
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table table-bordered align-middle">
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
                                    <td>
                                        @can('company-edit')
                                            <button class="btn btn-warning btn-sm editBtn" data-id="{{ $company->id }}"
                                                data-name="{{ $company->name }}" data-logo="{{ asset($company->logo) }}"
                                                data-hologram="{{ asset($company->hologram) }}"
                                                data-seal="{{ asset($company->seal) }}"
                                                data-signature="{{ asset($company->signature) }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('company-delete')
                                            <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete Company?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
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
            </div>
        </div>
    </div>

    {{-- add modal --}}
    <div class="modal fade" id="addCompanyModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Company Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Hologram</label>
                            <input type="file" name="hologram" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Seal</label>
                            <input type="file" name="seal" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Signature</label>
                            <input type="file" name="signature" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="editCompanyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit Company
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Company Name
                                </label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <!-- Logo -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Logo
                                </label>
                                <input type="file" name="logo" class="form-control">
                                <img id="edit_logo_preview" src="" class="mt-2 border rounded" width="100"
                                    height="100">
                            </div>
                            <!-- Hologram -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Hologram
                                </label>
                                <input type="file" name="hologram" class="form-control">
                                <img id="edit_hologram_preview" src="" class="mt-2 border rounded"
                                    width="100" height="100">
                            </div>
                            <!-- Seal -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Seal
                                </label>
                                <input type="file" name="seal" class="form-control">
                                <img id="edit_seal_preview" src="" class="mt-2 border rounded" width="100"
                                    height="100">
                            </div>
                            <!-- Signature -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Signature
                                </label>
                                <input type="file" name="signature" class="form-control">
                                <img id="edit_signature_preview" src="" class="mt-2 border rounded"
                                    width="150" height="100">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i>
                            Update Company
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
                document.getElementById('edit_logo_preview').src =
                    this.dataset.logo;
                document.getElementById('edit_hologram_preview').src =
                    this.dataset.hologram;
                document.getElementById('edit_seal_preview').src =
                    this.dataset.seal;
                document.getElementById('edit_signature_preview').src =
                    this.dataset.signature;
                document.getElementById('editForm').action =
                    "{{ url('/admin/company/update') }}/" + this.dataset.id;
                new bootstrap.Modal(
                    document.getElementById('editCompanyModal')
                ).show();
            });
        });
    </script>
@endpush
